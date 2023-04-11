<?php

namespace DMT\Test\Laposta\Api\Commands\MailingList;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\MailingList\PurgeMailingListSubscriptions;
use PHPUnit\Framework\TestCase;

class PurgeMailingListSubscriptionsTest extends TestCase
{
    public function testValidCommand(): void
    {
        $command = new PurgeMailingListSubscriptions('BaImMu3JZA');

        $validator = new ValidationMiddleware();
        $this->assertTrue($validator->execute($command, fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new PurgeMailingListSubscriptions(''), fn() => true);
    }
}
