<?php

namespace DMT\Test\Laposta\Api\Commands\MailingLists;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\MailingLists\DeleteMailingList;
use PHPUnit\Framework\TestCase;

class DeleteMailingListTest extends TestCase
{
    public function testValidCommand(): void
    {
        $command = new DeleteMailingList('BaImMu3JZA');

        $validator = new ValidationMiddleware();
        $this->assertTrue($validator->execute($command, fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new DeleteMailingList(''), fn() => true);
    }
}
