<?php

namespace DMT\Laposta\Api\Commands\Fields;

use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Interfaces\PostRequest;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use Symfony\Component\Validator\Constraints as Assert;

class CreateField implements PostRequest, DeserializableResponse
{
    /**
     * @Assert\Valid()
     */
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
        $this->field->id = null;
        $this->field->optionsFull = null;

        return $this->field;
    }

    public function toEntity(): string
    {
        return Field::class;
    }
}
