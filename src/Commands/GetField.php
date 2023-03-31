<?php

namespace DMT\Laposta\Api\Commands;

use DMT\Laposta\Api\Entity\Field;

class GetField implements \DMT\Laposta\Api\Interfaces\GetRequest
{
    private string $listId;
    private string $fieldId;

    public function __construct(string $listId, string $fieldId)
    {
        $this->listId = $listId;
        $this->fieldId = $fieldId;
    }

    public function getUri(): string
    {
        return sprintf('https://api.laposta.nl/v2/field/%s', $this->fieldId);
    }

    public function getQueryString(): ?string
    {
        return http_build_query(['list_id' => $this->listId]);
    }

    public function toEntity(): string
    {
        return Field::class;
    }
}
