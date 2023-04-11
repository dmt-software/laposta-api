<?php

namespace DMT\Laposta\Api\Commands\Fields;

use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Interfaces\PostRequest;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

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
            throw new ValidationException(
                sprintf('Invalid command %s given', self::class), 0, null, new ConstraintViolationList([
                    new ConstraintViolation(
                        'Value can not be empty',
                        null,
                        [],
                        '',
                        'field.id',
                        $this->field->id
                    )
                ])
            );
        }

        return sprintf('https://api.laposta.nl/v2/field/%s', $this->field->id);
    }

    public function getPayload(): object
    {
        if (!$this->field->listId) {
            throw new ValidationException(
                sprintf('Invalid command %s given', self::class), 0, null, new ConstraintViolationList([
                    new ConstraintViolation(
                        'Value can not be empty',
                        null,
                        [],
                        '',
                        'field.listId',
                        $this->field->listId
                    )
                ])
            );
        }

        /** empty the field options array to ensure a more predictable result */
        $this->field->options = null;

        return $this->field;
    }

    public function toEntity(): string
    {
        return Field::class;
    }
}
