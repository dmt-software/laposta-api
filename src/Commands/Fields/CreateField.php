<?php

namespace DMT\Laposta\Api\Commands\Fields;

use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Interfaces\PostRequest;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;

class CreateField implements PostRequest, DeserializableResponse
{
    private Field $field;

    public function __construct(Field $field)
    {
        $this->field = $field;
    }

    public function getUri(): string
    {
        return 'https://api.laposta.nl/v2/field';
    }

    public function getPayload(): Field
    {
        return $this->field;
    }

    public function toEntity(): string
    {
        return Field::class;
    }
}
