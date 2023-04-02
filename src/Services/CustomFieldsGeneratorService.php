<?php

namespace DMT\Laposta\Api\Services;

use DateTime;
use DateTimeInterface;
use DMT\Laposta\Api\Clients\Fields;
use DMT\Laposta\Api\Config;

class CustomFieldsGeneratorService
{
    private Config $config;
    private Fields $fields;

    public function __construct(Config $config, Fields $fields)
    {
        $this->config = $config;
        $this->fields = $fields;
    }

    public function checkEntity(string $listId): bool
    {
        $entity = $this->config->customFieldsClasses[$listId] ?? '-not-rendered-';

        if (!class_exists($entity)) {
            return false;
        }

        $class = new \ReflectionClass($entity);
        if (!preg_match('~@version\s(?<date>[^*]+)~ms', $class->getDocComment(), $match)) {
            return false;
        }

        $generatedOn = DateTime::createFromFormat(DateTimeInterface::RFC7231, trim($match['date']));

        $collection = $this->fields->all($listId);
        foreach ($collection->fields as $field) {
            if ($field->created > $generatedOn || $field->modified > $generatedOn) {
                return false;
            }
        }

        return true;
    }
}
