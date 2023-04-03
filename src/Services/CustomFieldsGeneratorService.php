<?php

namespace DMT\Laposta\Api\Services;

use DateTime;
use DateTimeInterface;
use DMT\Laposta\Api\Clients\Fields;
use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Interfaces\TemplateParser;
use DMT\Laposta\Api\Services\Bindings\ClassBinding;
use DMT\Laposta\Api\Services\Bindings\PropertyBinding;
use DMT\Laposta\Api\Services\Parsers\NativePhpParser;

class CustomFieldsGeneratorService
{
    private Config $config;
    private Fields $fields;
    private TemplateParser $parser;

    public function __construct(Config $config, Fields $fields, TemplateParser $parser = null)
    {
        $this->config = $config;
        $this->fields = $fields;
        $this->parser = $parser ?? new NativePhpParser();
    }

    public function generateEntity(string $listId, string $toEntityClassName, string $output): void
    {
        $version = date(DateTime::RFC7231, strtotime('now'));
        $class = new ClassBinding($toEntityClassName);

        $collection = $this->fields->all($listId);
        foreach ($collection->fields as $field) {
            $prop = new PropertyBinding();
            $prop->name = $field->customName;
            $prop->required = $field->required;
            $prop->default = $field->defaultvalue;

            if (strpos($field->datatype, 'select') === 0 && !in_array($prop->default, (array)$field->options, true)) {
                $prop->default = null;
            }
            if ($prop->default && $prop->type !== 'numeric') {
                $prop->default = sprintf("'%s'", $prop->default);
            }

            if ($field->datatype == 'date') {
                $prop->type = DateTime::class;
                $prop->default = null;
            } elseif ($field->datatype == 'numeric') {
                $prop->type = 'float';
                $prop->default = is_numeric($field->defaultvalue) ? $field->defaultvalue : null;
                if ($prop->default && !preg_match('~\d\.\d~', $prop->default)) {
                    $prop->type = 'int';
                }
            } elseif ($field->datatype == 'select_multiple') {
                $prop->type = 'array';
                $prop->default = $prop->default ? sprintf('[%s]', $prop->default) : null;
            }

            $class->properties[] = $prop;
        }

        file_put_contents($output, $this->parser->parse(compact('listId', 'class', 'version')));
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
