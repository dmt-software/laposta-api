<?php

namespace DMT\Laposta\Api\Commands\Webhooks;

use DMT\Laposta\Api\Entity\Webhook;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Interfaces\PostRequest;
use Symfony\Component\Validator\Constraints as Assert;

class CreateWebhook implements PostRequest, DeserializableResponse
{
    /**
     * @Assert\Valid()
     */
    private Webhook $webhook;

    public function __construct(Webhook $webhook)
    {
        $this->webhook = $webhook;
    }

    public function getUri(): string
    {
        return 'https://api.laposta.nl/v2/webhook';
    }

    public function getPayload(): object
    {
        return $this->webhook;
    }

    public function toEntity(): string
    {
        return Webhook::class;
    }
}
