<?php

namespace DMT\Test\Laposta\Api\Commands\Subscribers;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\Subscribers\CreateSubscriber;
use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Test\Laposta\Api\Fixtures\CustomFields;
use PHPUnit\Framework\TestCase;

class CreateSubscriberTest extends TestCase
{
    public function testValidCommand(): void
    {
        $subscriber = new Subscriber();
        $subscriber->listId = 'BaImMu3JZA';
        $subscriber->email = 'user@example.org';
        $subscriber->ip = '127.0.0.1';

        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new CreateSubscriber($subscriber), fn() => true));
    }

    public function testInvalidCommandDueToMissingRequiredCustomFields(): void
    {
        $this->expectException(ValidationException::class);

        $subscriber = new Subscriber();
        $subscriber->listId = 'BaImMu3JZA';
        $subscriber->email = 'user@example.org';
        $subscriber->ip = '127.0.0.1';
        $subscriber->customFields = new CustomFields();

        $validator = new ValidationMiddleware();
        $validator->execute(new CreateSubscriber($subscriber), fn() => true);
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new CreateSubscriber(new Subscriber()), fn() => true);
    }
}
