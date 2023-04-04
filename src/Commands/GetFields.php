<?php

namespace DMT\Laposta\Api\Commands;

use DMT\Laposta\Api\Entity\FieldCollection;
use DMT\Laposta\Api\Interfaces\GetRequest;
use Symfony\Component\Validator\Constraints as Assert;

class GetFields implements GetRequest
{
    /**
     * @Assert\NotBlank()
     */
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
        return FieldCollection::class;
    }
}
