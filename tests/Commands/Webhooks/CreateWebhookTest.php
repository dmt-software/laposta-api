<?php

namespace DMT\Test\Laposta\Api\Commands\Webhooks;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\Webhooks\CreateWebhook;
use DMT\Laposta\Api\Entity\Webhook;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CreateWebhookTest extends TestCase
{
    public function testValidCommand(): void
    {
        $webhook = new Webhook();
        $webhook->listId = 'BaImMu3JZA';
        $webhook->event = Webhook::EVENT_SUBSCRIBED;
        $webhook->url = 'http://example.org/hooked';

        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new CreateWebhook($webhook), fn() => true));
    }

    #[DataProvider('invalidWebhookProvider')]
    public function testInvalidCommand(Webhook $webhook): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new CreateWebhook($webhook), fn() => true);
    }

    public static function invalidWebhookProvider(): iterable
    {
        yield 'empty webhook' => [new Webhook()];

        $webhook = new Webhook();
        $webhook->listId = 'BaImMu3JZA';
        $webhook->url = 'http://example.org/hooked';

        yield 'missing event for webhook' => [$webhook];

        $webhook = new Webhook();
        $webhook->listId = '';
        $webhook->event = Webhook::EVENT_DEACTIVATED;
        $webhook->url = 'http://example.org/hooked';

        yield 'empty list id' => [$webhook];

        $webhook = new Webhook();
        $webhook->listId = 'BaImMu3JZA';
        $webhook->event = Webhook::EVENT_MODIFIED;
        $webhook->url = '/local/path';

        yield 'wrong url set' => [$webhook];

    }
}
