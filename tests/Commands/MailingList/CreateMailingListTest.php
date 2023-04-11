<?php

namespace DMT\Test\Laposta\Api\Commands\MailingList;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\MailingList\CreateMailingList;
use DMT\Laposta\Api\Entity\MailingList;
use PHPUnit\Framework\TestCase;

class CreateMailingListTest extends TestCase
{
    public function testValidCommand(): void
    {
        $mailingList = new MailingList();
        $mailingList->name = 'Test list';

        $command = new CreateMailingList($mailingList);

        $validator = new ValidationMiddleware();
        $this->assertTrue($validator->execute($command, fn() => true));
    }

    /**
     * @dataProvider invalidCommandProvider
     */
    public function testInvalidCommand(MailingList $mailingList): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new CreateMailingList($mailingList), fn() => true);
    }

    public function invalidCommandProvider(): iterable
    {
        yield 'missing list name' => [new MailingList()];

        $mailingList = new MailingList();
        $mailingList->name = '';

        yield 'empty list name' => [$mailingList];

        $mailingList = new MailingList();
        $mailingList->name = 'Test list';
        $mailingList->subscribeNotificationEmail = 'foo';

        yield 'invalid subscribe email' => [$mailingList];

        $mailingList = new MailingList();
        $mailingList->name = 'Test list';
        $mailingList->unsubscribeNotificationEmail = 'foo';

        yield 'invalid unsubscribe email' => [$mailingList];
    }
}
