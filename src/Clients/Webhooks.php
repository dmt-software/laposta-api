<?php

namespace DMT\Laposta\Api\Clients;

use DMT\Laposta\Api\Commands\Webhooks\CreateWebhook;
use DMT\Laposta\Api\Commands\Webhooks\DeleteWebhook;
use DMT\Laposta\Api\Commands\Webhooks\GetWebhook;
use DMT\Laposta\Api\Commands\Webhooks\GetWebhooks;
use DMT\Laposta\Api\Commands\Webhooks\UpdateWebhook;
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

    /**
     * Adding a webhook
     *
     * @param Webhook $webhook The webhook to add
     */
    public function create(Webhook $webhook): void
    {
        $this->commandBus->handle(new CreateWebhook($webhook));
    }

    /**
     * Modifying a webhook
     *
     * @param Webhook $webhook The modified webhook
     */
    public function update(Webhook $webhook): void
    {
        $this->commandBus->handle(new UpdateWebhook($webhook));
    }

    /**
     * Deleting a webhook
     *
     * @param string $listId The ID of the list to which the webhook belongs
     * @param string $webhookId The ID of the webhook to be deleted
     */
    public function delete(string $listId, string $webhookId): void
    {
        $this->commandBus->handle(new DeleteWebhook($listId, $webhookId));
    }
}
