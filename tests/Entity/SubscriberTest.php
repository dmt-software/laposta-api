<?php

namespace DMT\Test\Laposta\Api\Entity;

use DMT\Laposta\Api\Config;
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
}
