<?php

namespace DMT\Test\Laposta\Api\Clients;

use DateTimeInterface;
use DMT\Laposta\Api\Clients\Webhooks;
use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Entity\Webhook;
use DMT\Laposta\Api\Factories\CommandBusFactory;
use DMT\Test\Laposta\Api\Fixtures\CustomFields;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class WebhooksTest extends TestCase
{
    public function testAll(): void
    {
        $webhooks = new Webhooks(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/webhooks.json')
            )
        );

        $this->assertCount(2, $webhooks->all('BaImMu3JZA'));
    }

    public function testGet(): void
    {
        $webhooks = new Webhooks(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/webhook.json')
            )
        );

        $webhook = $webhooks->get('BaImMu3JZA', 'cW5ls8IVJl');

        $this->assertSame('cW5ls8IVJl', $webhook->id);
        $this->assertSame('BaImMu3JZA', $webhook->listId);
    }

    public function testCreate(): void
    {
        $webhooks = new Webhooks(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/webhook.json')
            )
        );

        $webhook = new Webhook();
        $webhook->listId = 'BaImMu3JZA';
        $webhook->event = 'subscribed';
        $webhook->url = 'http://example.net/webhook.asp';

        $webhooks->create($webhook);

        $this->assertNotNull($webhook->id);
        $this->assertSame('BaImMu3JZA', $webhook->listId);
        $this->assertInstanceOf(DateTimeInterface::class, $webhook->created);
    }

    public function testUpdate(): void
    {
        $webhooks = new Webhooks(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/webhook.json')
            )
        );

        $webhook = new Webhook();
        $webhook->id = 'cW5ls8IVJl';
        $webhook->listId = 'BaImMu3JZA';
        $webhook->modified = null;

        $webhooks->update($webhook);

        $this->assertSame('cW5ls8IVJl', $webhook->id);
        $this->assertSame('BaImMu3JZA', $webhook->listId);
        $this->assertInstanceOf(DateTimeInterface::class, $webhook->modified);
    }

    public function testDelete(): void
    {
        $webhooks = new Webhooks(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/webhook.json')
            )
        );

        $webhooks->delete('BaImMu3JZA', 'cW5ls8IVJl');

        $this->expectNotToPerformAssertions();
    }

    private function getTestConfig(string $testResponse): Config
    {
        $handler = HandlerStack::create(
            new MockHandler([
                new Response(200, [], file_get_contents($testResponse)),
            ])
        );

        $config = new Config();
        $config->apiKey = 'JdMtbsMq2jqJdQZD9AHC';
        $config->customFieldsClasses = [
            'BaImMu3JZA' => CustomFields::class,
        ];
        $config->httpClient = new Client(compact('handler'));

        return $config;
    }
}
