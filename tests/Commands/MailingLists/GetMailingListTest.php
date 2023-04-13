<?php

namespace DMT\Test\Laposta\Api\Commands\MailingLists;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\MailingLists\GetMailingList;
use PHPUnit\Framework\TestCase;

class GetMailingListTest extends TestCase
{
    public function testValidCommand(): void
    {
        $command = new GetMailingList('BaImMu3JZA');

        $validator = new ValidationMiddleware();
        $this->assertTrue($validator->execute($command, fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new GetMailingList(''), fn() => true);
    }
}
