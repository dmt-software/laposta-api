<?php

namespace DMT\Laposta\Api\Clients;

use DMT\Laposta\Api\Commands\Webhooks\GetWebhook;
use DMT\Laposta\Api\Commands\Webhooks\GetWebhooks;
use DMT\Laposta\Api\Entity\Webhook;
use DMT\Laposta\Api\Entity\WebhookCollection;
use League\Tactician\CommandBus;

class Webhooks
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * Requesting all webhooks of a list
     *
     * @param string $listId The ID of the list to which the webhooks belong
     *
     * @return \DMT\Laposta\Api\Entity\WebhookCollection
     */
    public function all(string $listId): WebhookCollection
    {
        return $this->commandBus->handle(new GetWebhooks($listId));
    }

    /**
     * Requesting a webhook
     *
     * @param string $listId The ID of the list to which the field belongs
     * @param string $webhookId The ID of the list to be requested
     *
     * @return Webhook
     */
    public function get(string $listId, string $webhookId): Webhook
    {
        return $this->commandBus->handle(new GetWebhook($listId, $webhookId));
    }
}
