<?php

namespace DMT\Test\Laposta\Api\Clients;

use DateTimeInterface;
use DMT\Laposta\Api\Clients\MailingLists;
use DMT\Laposta\Api\Commands\MailingList\BulkMailingListSubscriptions;
use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Entity\Error;
use DMT\Laposta\Api\Entity\MailingList;
use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Factories\CommandBusFactory;
use DMT\Test\Laposta\Api\Fixtures\CustomFields;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class MailingListsTest extends TestCase
{
    public function testAll(): void
    {
        $lists = new MailingLists(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/lists.json')
            )
        );

        $this->assertCount(2, $lists->all());
    }

    public function testGet(): void
    {
        $lists = new MailingLists(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/list.json')
            )
        );

        $this->assertSame('BaImMu3JZA', $lists->get('BaImMu3JZA')->id);
    }

    public function testCreate(): void
    {
        $lists = new MailingLists(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/list.json')
            )
        );

        $list = new MailingList();
        $list->name = 'Testlist';
        $list->subscribeNotificationEmail = 'subscription@example.net';

        $lists->create($list);

        $this->assertNotNull($list->id);
        $this->assertSame('subscription@example.net', $list->subscribeNotificationEmail);
        $this->assertInstanceOf(DateTimeInterface::class, $list->created);
    }

    public function testUpdate(): void
    {
        $lists = new MailingLists(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/list.json')
            )
        );

        $list = new MailingList();
        $list->id = 'BaImMu3JZA';
        $list->unsubscribeNotificationEmail = 'unsubscription@example.net';

        $lists->update($list);

        $this->assertSame('BaImMu3JZA', $list->id);
        $this->assertSame('unsubscription@example.net', $list->unsubscribeNotificationEmail);
        $this->assertInstanceOf(DateTimeInterface::class, $list->modified);
    }

    public function testDelete(): void
    {
        $lists = new MailingLists(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/list.json')
            )
        );

        $lists->delete('BaImMu3JZA');

        $this->expectNotToPerformAssertions();
    }

    public function testPurge(): void
    {
        $lists = new MailingLists(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/list.json')
            )
        );

        $lists->purge('BaImMu3JZA');

        $this->expectNotToPerformAssertions();
    }

    public function testBulk(): void
    {
        $lists = new MailingLists(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/list-report.json')
            )
        );

        $subscribers = [];
        $subscribers[0] = new Subscriber();
        $subscribers[0]->email = 'member1@example.org';
        $subscribers[1] = new Subscriber();
        $subscribers[1]->id = '9978ydioiZ';
        $subscribers[1]->email = 'member2@example.org';
        $subscribers[2] = new Subscriber();
        $subscribers[2]->email = 'member3@example.org';
        $subscribers[2]->customFields = new CustomFields();
        $subscribers[2]->customFields->name = 'set';

        $report = $lists->bulk('BaImMu3JZA', $subscribers, BulkMailingListSubscriptions::INSERT);

        $this->assertSame(3, $report->summary->provided);
        $this->assertSame(1, $report->summary->errors);
        $this->assertSame(1, $report->summary->skipped);
        $this->assertSame(0, $report->summary->edited);
        $this->assertSame(1, $report->summary->added);
        $this->assertContainsOnlyInstancesOf(Error::class, $report->errors);
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
