<?php

namespace DMT\Laposta\Api\Commands\Webhooks;

use DMT\Laposta\Api\Interfaces\DeleteRequest;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteWebhook implements DeleteRequest
{
    /**
     * @Assert\NotBlank()
     */
    private string $listId;

    /**
     * @Assert\NotBlank()
     */
    private string $webhookId;

    public function __construct(string $listId, string $webhookId)
    {
        $this->listId = $listId;
        $this->webhookId = $webhookId;
    }

    public function getUri(): string
    {
        return sprintf('https://api.laposta.nl/v2/webhook/%s', $this->webhookId);
    }

    public function getQueryString(): string
    {
        return http_build_query(['list_id' => $this->listId]);
    }
}
