<?php

namespace DMT\Test\Laposta\Api\Commands\Webhooks;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\Webhooks\UpdateWebhook;
use DMT\Laposta\Api\Entity\Webhook;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UpdateWebhookTest extends TestCase
{
    public function testValidCommand(): void
    {
        $webhook = new Webhook();
        $webhook->id = 'cW5ls8IVJl';
        $webhook->listId = 'BaImMu3JZA';
        $webhook->blocked = true;

        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new UpdateWebhook($webhook), fn() => true));
    }

    #[DataProvider('invalidWebhookProvider')]
    public function testInvalidCommand(Webhook $webhook): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new UpdateWebhook($webhook), fn($command) => $command->getUri() && $command->getPayload());
    }

    public static function invalidWebhookProvider(): iterable
    {
        $webhook = new Webhook();
        $webhook->id = '';
        $webhook->listId = 'BaImMu3JZA';

        yield 'empty id for webhook' => [$webhook];

        $webhook = new Webhook();
        $webhook->id = 'cW5ls8IVJl';
        $webhook->listId = '';

        yield 'empty list id' => [$webhook];
    }
}
