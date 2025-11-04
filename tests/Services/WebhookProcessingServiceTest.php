<?php

namespace DMT\Test\Laposta\Api\Services;

use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Entity\Event;
use DMT\Laposta\Api\Entity\EventInformation;
use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Entity\Webhook;
use DMT\Laposta\Api\Factories\SerializerFactory;
use DMT\Laposta\Api\Services\Processors\CallbackEventProcessor;
use DMT\Laposta\Api\Services\WebhookProcessingService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class WebhookProcessingServiceTest extends TestCase
{
    public function testProcess(): void
    {
        $callback = function (Event $event): void {
            $this->assertSame(EventInformation::DEACTIVATE_ACTION_DELETED, $event->info->action);
            $this->assertInstanceOf(Subscriber::class, $event->subscriber);
        };

        $processingService = new WebhookProcessingService(
            SerializerFactory::create(new Config()),
            new CallbackEventProcessor($callback)
        );

        $processingService->process(file_get_contents(__DIR__ . '/../Fixtures/events.json'));
    }

    #[DataProvider('missingWebhookEventTypeProvider')]
    public function testProcessNotConfiguredWebhookEvent(string $type): void
    {
        $webhookJson = str_replace(
            '"deactivated"',
            sprintf('"%s"', $type),
            file_get_contents(__DIR__ . '/../Fixtures/events.json')
        );

        $processingService = new WebhookProcessingService(
            SerializerFactory::create(new Config()),
            new CallbackEventProcessor('var_dump')
        );

        $this->expectException(RuntimeException::class);

        $processingService->process($webhookJson);
    }

    public static function missingWebhookEventTypeProvider(): iterable
    {
        return [
            'missing subscribed event' => [Webhook::EVENT_SUBSCRIBED],
            'missing modified event' => [Webhook::EVENT_MODIFIED],
        ];
    }
}
