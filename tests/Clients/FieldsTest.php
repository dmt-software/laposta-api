<?php

namespace DMT\Test\Laposta\Api\Clients;

use DateTimeInterface;
use DMT\Laposta\Api\Clients\Fields;
use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Factories\CommandBusFactory;
use DMT\Test\Laposta\Api\Fixtures\CustomFields;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class FieldsTest extends TestCase
{
    public function testAll(): void
    {
        $fields = new Fields(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/fields.json')
            )
        );

        $this->assertCount(2, $fields->all('BaImMu3JZA'));
    }

    public function testGet(): void
    {
        $fields = new Fields(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/field.json')
            )
        );

        $field = $fields->get('BaImMu3JZA', 'gt2Em8vJwi');

        $this->assertSame('gt2Em8vJwi', $field->id);
        $this->assertSame('BaImMu3JZA', $field->listId);
    }

    public function testCreate(): void
    {
        $fields = new Fields(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/field.json')
            )
        );

        $field = new Field();
        $field->listId = 'BaImMu3JZA';
        $field->name = 'Name';
        $field->datatype = 'text';
        $field->created = null;

        $fields->create($field);

        $this->assertNotNull($field->id);
        $this->assertSame('BaImMu3JZA', $field->listId);
        $this->assertInstanceOf(DateTimeInterface::class, $field->created);
    }

    public function testUpdate(): void
    {
        $fields = new Fields(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/field.json')
            )
        );

        $field = new Field();
        $field->id = 'gt2Em8vJwi';
        $field->listId = 'BaImMu3JZA';
        $field->name = 'Name';
        $field->datatype = 'text';
        $field->modified = null;

        $fields->update($field);

        $this->assertSame('gt2Em8vJwi', $field->id);
        $this->assertSame('BaImMu3JZA', $field->listId);
        $this->assertInstanceOf(DateTimeInterface::class, $field->modified);
    }

    public function testDelete(): void
    {
        $fields = new Fields(
            CommandBusFactory::create(
                $this->getTestConfig(__DIR__ . '/../Fixtures/field.json')
            )
        );

        $fields->delete('BaImMu3JZA', 'gt2Em8vJwi');

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
