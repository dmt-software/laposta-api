<?php

namespace DMT\Laposta\Api\Commands\Webhooks;

use DMT\Laposta\Api\Entity\WebhookCollection;
use Symfony\Component\Validator\Constraints as Assert;

class GetWebhooks implements \DMT\Laposta\Api\Interfaces\GetRequest
{
    #[Assert\NotBlank]
    private string $listId;

    public function __construct(string $listId)
    {
        $this->listId = $listId;
    }

    public function getUri(): string
    {
        return 'https://api.laposta.nl/v2/webhook';
    }

    public function getQueryString(): string
    {
        return http_build_query(['list_id' => $this->listId]);
    }

    public function toEntity(): string
    {
        return WebhookCollection::class;
    }
}
