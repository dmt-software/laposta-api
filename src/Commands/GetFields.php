<?php

namespace DMT\Laposta\Api\Commands;

use DMT\Laposta\Api\Entity\Fields;
use DMT\Laposta\Api\Interfaces\GetRequest;

class GetFields implements GetRequest
{
    private string $listId;

    public function __construct(string $listId)
    {
        $this->listId = $listId;
    }

    public function getUri(): string
    {
        return 'https://api.laposta.nl/v2/field';
    }

    public function getQueryString(): ?string
    {
        return http_build_query(['list_id' => $this->listId]);
    }

    public function toEntity(): string
    {
        return Fields::class;
    }
}
