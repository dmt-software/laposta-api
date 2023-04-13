<?php

namespace DMT\Laposta\Api\Commands\Webhooks;

use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Entity\Webhook;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Interfaces\PostRequest;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class UpdateWebhook implements PostRequest, DeserializableResponse
{
    private Webhook $webhook;

    public function __construct(Webhook $webhook)
    {
        $this->webhook = $webhook;
    }

    public function getUri(): string
    {
        if (!$this->webhook->id) {
            throw new ValidationException(
                sprintf('Invalid command %s given', self::class), 0, null, new ConstraintViolationList([
                    new ConstraintViolation(
                        'Value can not be empty',
                        null,
                        [],
                        '',
                        'webhook.id',
                        $this->webhook->id
                    )
                ])
            );
        }

        return sprintf('https://api.laposta.nl/v2/webhook/%s', $this->webhook->id);
    }

    public function getPayload(): object
    {
        if (!$this->webhook->listId) {
            throw new ValidationException(
                sprintf('Invalid command %s given', self::class), 0, null, new ConstraintViolationList([
                    new ConstraintViolation(
                        'Value can not be empty',
                        null,
                        [],
                        '',
                        'webhook.listId',
                        $this->webhook->listId
                    )
                ])
            );
        }

        return $this->webhook;
    }

    public function toEntity(): string
    {
        return Webhook::class;
    }
}
