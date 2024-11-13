<?php

namespace DMT\Test\Laposta\Api\Entity;

use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Entity\CustomField;
use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Factories\SerializerFactory;
use DMT\Test\Laposta\Api\Fixtures\CustomFields;
use JMS\Serializer\SerializationContext;
use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{
    public function testSerializeForPost(): void
    {
        $subscriber = new Subscriber();
        $subscriber->listId = 'BaImMu3JZA';
        $subscriber->email = 'test@example.org';
        $subscriber->ip = '127.0.0.1';
        $subscriber->customFields = new CustomFields();
        $subscriber->customFields->name = 'Jane Do';

        $payload = SerializerFactory::create(new Config())->serialize(
            $subscriber,
            'http-post',
            SerializationContext::create()->setGroups('Default')
        );

        $this->assertStringContainsString('list_id=BaImMu3JZA', $payload);
        $this->assertStringContainsString('email=test%40example.org', $payload);
        $this->assertStringContainsString('ip=127.0.0.1', $payload);
        $this->assertStringContainsString('custom_fields%5Bname%5D=Jane+Do', $payload);
    }

    /** @dataProvider provideSubscriberJson */
    public function testDeserializeSubscriberWithCustomFieldsEntity(
        string $subscriberJson,
        string $emptyField,
        $expectedEmptyValue
    ) {
        $config = Config::fromArray([
            'customFieldsClasses' => [
                'BaImMu3JZA' => CustomFields::class,
            ],
        ]);

        $subscriber = SerializerFactory::create($config)->deserialize(
            $subscriberJson,
            Subscriber::class,
            'json'
        );

        $this->assertInstanceOf(Subscriber::class, $subscriber);
        $this->assertInstanceOf(CustomFields::class, $subscriber->customFields);
        $this->assertSame($expectedEmptyValue, $subscriber->customFields->$emptyField);
    }

    /** @dataProvider provideSubscriberJson */
    public function testDeserializeSubscriberWithoutCustomFieldsEntity(string $subscriberJson, string $emptyField)
    {
        $subscriber = SerializerFactory::create(new Config())->deserialize(
            $subscriberJson,
            Subscriber::class,
            'json'
        );

        $this->assertInstanceOf(Subscriber::class, $subscriber);
        $this->assertContainsOnlyInstancesOf(CustomField::class, $subscriber->customFields->field);

        $fields = [];
        array_walk(
            $subscriber->customFields->field,
            function(CustomField $field) use (&$fields) {
                $fields[$field->name] = $field;
            }
        );

        $this->assertEmpty($fields[$emptyField]->value);
    }

    public function provideSubscriberJson(): iterable
    {
        $json = file_get_contents(__DIR__ . '/../Fixtures/subscriber.json');

        $subscriber = json_decode($json, true, 512, JSON_OBJECT_AS_ARRAY);
        $subscriber['member']['custom_fields']['name'] = "";

        yield 'json with empty string' => [json_encode($subscriber), 'name', ""];

        $subscriber = json_decode($json, true, 512, JSON_OBJECT_AS_ARRAY);
        $subscriber['member']['custom_fields']['dateofbirth'] = "";

        yield 'json with empty datetime' => [json_encode($subscriber), "dateofbirth", null];

        $subscriber = json_decode($json, true, 512, JSON_OBJECT_AS_ARRAY);
        $subscriber['member']['custom_fields']['children'] = "";

        yield 'json with empty integer' => [json_encode($subscriber), "children", 0];

        $subscriber = json_decode($json, true, 512, JSON_OBJECT_AS_ARRAY);
        $subscriber['member']['custom_fields']['prefs'] = [];

        yield 'json with empty array' => [json_encode($subscriber), 'prefs', []];
    }
}
