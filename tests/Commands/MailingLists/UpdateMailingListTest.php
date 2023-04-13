<?php

namespace DMT\Test\Laposta\Api\Commands\MailingLists;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\MailingLists\UpdateMailingList;
use DMT\Laposta\Api\Entity\MailingList;
use PHPUnit\Framework\TestCase;

class UpdateMailingListTest extends TestCase
{
    public function testValidCommand(): void
    {
        $mailingList = new MailingList();
        $mailingList->id = 'BaImMu3JZA';

        $command = new UpdateMailingList($mailingList);

        $validator = new ValidationMiddleware();
        $this->assertTrue($validator->execute($command, fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new UpdateMailingList(new MailingList()), fn($command) => $command->getUri());
    }
}
