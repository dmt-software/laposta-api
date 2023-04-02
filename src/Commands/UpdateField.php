<?php

namespace DMT\Laposta\Api\Commands;

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
        return sprintf('https://api.laposta.nl/v2/field/%s', $this->field->id);
    }

    public function getPayload(): object
    {
        return $this->field;
    }

    public function toEntity(): string
    {
        return Field::class;
    }
}
