<?php

namespace DMT\Test\Laposta\Api\Clients;

use DateTime;
use DateTimeInterface;
use DMT\Laposta\Api\Clients\Subscribers;
use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Factories\CommandBusFactory;
use DMT\Test\Laposta\Api\Fixtures\CustomFields;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class SubscribersTest extends TestCase
{
    public function testAll(): void
    {
        $subscribers = new Subscribers(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/subscribers.json')
            )
        );

        $this->assertCount(2, $subscribers->all('BaImMu3JZA'));
    }

    public function testGet(): void
    {
        $subscribers = new Subscribers(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/subscriber.json')
            )
        );

        $subscriber = $subscribers->get('BaImMu3JZA', '9978ydioiZ');
var_export($subscriber);
        $this->assertSame('9978ydioiZ', $subscriber->id);
        $this->assertSame('BaImMu3JZA', $subscriber->listId);
    }

    public function testCreate()
    {
        $subscribers = new Subscribers(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/subscriber.json')
            )
        );

        $subscriber = new Subscriber();
        $subscriber->listId = 'BaImMu3JZA';
        $subscriber->email = 'maartje@example.net';
        $subscriber->ip = '198.51.100.10';
        $subscriber->customFields = new CustomFields();
        $subscriber->customFields->name = 'Maartje de Vries - Abbink';
        $subscriber->customFields->dateofbirth = new DateTime('1973-05-10 00:00:00');
        $subscriber->customFields->children = 3;
        $subscriber->customFields->prefs = [
            'optionA',
            'optionB'
        ];

        $subscribers->create($subscriber);

        $this->assertSame('9978ydioiZ', $subscriber->id);
        $this->assertSame('BaImMu3JZA', $subscriber->listId);
        $this->assertSame('maartje@example.net', $subscriber->email);
        $this->assertSame('198.51.100.10', $subscriber->ip);
        $this->assertSame('active', $subscriber->state);
        $this->assertSame('Maartje de Vries - Abbink', $subscriber->customFields->name);
        $this->assertInstanceOf(DateTimeInterface::class, $subscriber->signupDate);
    }

    public function testUpdate(): void
    {
        $subscribers = new Subscribers(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/subscriber.json')
            )
        );

        $subscriber = new Subscriber();
        $subscriber->id = '9978ydioiZ';
        $subscriber->listId = 'BaImMu3JZA';
        $subscriber->customFields = new CustomFields();
        $subscriber->customFields->children = 3;

        $subscribers->update($subscriber);

        $this->assertSame('9978ydioiZ', $subscriber->id);
        $this->assertSame('BaImMu3JZA', $subscriber->listId);
        $this->assertSame('maartje@example.net', $subscriber->email);
        $this->assertSame('198.51.100.10', $subscriber->ip);
        $this->assertSame('active', $subscriber->state);
        $this->assertSame(3, $subscriber->customFields->children);
    }

    public function testDelete(): void
    {
        $subscribers = new Subscribers(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/subscriber.json')
            )
        );

        $subscribers->delete('BaImMu3JZA', '9978ydioiZ');

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
