<?php

namespace DMT\Laposta\Api\Commands\Fields;

use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Interfaces\PostRequest;

class UpdateField implements PostRequest, DeserializableResponse
{
    private Field $field;

    public function __construct(Field $field)
    {
        $this->field = $field;
    }

    public function getUri(): string
    {
        if (!$this->field->id) {
            throw new ValidationException('Field id can not be empty');
        }

        return sprintf('https://api.laposta.nl/v2/field/%s', $this->field->id);
    }

    public function getPayload(): object
    {
        if (!$this->field->listId) {
            throw new ValidationException('Field listId can not be empty');
        }

        return $this->field;
    }

    public function toEntity(): string
    {
        return Field::class;
    }
}
