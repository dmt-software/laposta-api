<?php

namespace DMT\Test\Laposta\Api\Commands\MailingLists;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\MailingLists\BulkMailingListSubscriptions;
use DMT\Laposta\Api\Entity\Subscriber;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BulkMailingListSubscriptionsTest extends TestCase
{
    public function testValidCommand(): void
    {
        $command = new BulkMailingListSubscriptions('BaImMu3JZA', [new Subscriber()]);

        $validator = new ValidationMiddleware();
        $this->assertTrue($validator->execute($command, fn() => true));
    }

    #[DataProvider('invalidCommandProvider')]
    public function testInvalidCommand(BulkMailingListSubscriptions $command): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute($command, fn() => true);
    }

    public static function invalidCommandProvider(): iterable
    {
        yield 'purge list id' => [
            new BulkMailingListSubscriptions('', [new Subscriber()])
        ];

        yield 'purge list of subscribers' => [
            new BulkMailingListSubscriptions('BaImMu3JZA', [])
        ];

        yield 'to many subscribers' => [
            new BulkMailingListSubscriptions('BaImMu3JZA', array_fill(0, 100_001, new Subscriber()))
        ];
    }
}
