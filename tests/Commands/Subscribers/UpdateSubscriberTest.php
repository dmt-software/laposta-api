<?php

namespace DMT\Test\Laposta\Api\Commands\Subscribers;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\Subscribers\UpdateSubscriber;
use DMT\Laposta\Api\Entity\Subscriber;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UpdateSubscriberTest extends TestCase
{
    public function testValidCommand(): void
    {
        $subscriber = new Subscriber();
        $subscriber->id = '9978ydioiZ';
        $subscriber->listId = 'BaImMu3JZA';

        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new UpdateSubscriber($subscriber), fn() => true));
    }

    #[DataProvider('invalidSubscriberProvider')]
    public function testInvalidCommand(Subscriber $subscriber): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new UpdateSubscriber($subscriber), fn($command) => $command->getUri() && $command->getPayload());
    }

    public static function invalidSubscriberProvider(): iterable
    {
        $subscriber = new Subscriber();
        $subscriber->id = '9978ydioiZ';
        $subscriber->listId = '';


        return [
            'update without id' => [new Subscriber()],
            'update without list' => [$subscriber]
        ];
    }
}
