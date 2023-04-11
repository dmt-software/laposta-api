<?php

namespace DMT\Laposta\Api\Clients;

use DMT\Laposta\Api\Commands\MailingList\BulkMailingListSubscriptions;
use DMT\Laposta\Api\Commands\MailingList\CreateMailingList;
use DMT\Laposta\Api\Commands\MailingList\DeleteMailingList;
use DMT\Laposta\Api\Commands\MailingList\PurgeMailingListSubscriptions;
use DMT\Laposta\Api\Commands\MailingList\GetMailingList;
use DMT\Laposta\Api\Commands\MailingList\GetMailingLists;
use DMT\Laposta\Api\Commands\MailingList\UpdateMailingList;
use DMT\Laposta\Api\Entity\MailingList;
use DMT\Laposta\Api\Entity\MailingListCollection;
use DMT\Laposta\Api\Entity\SubscriptionsReport;
use League\Tactician\CommandBus;

class MailingLists
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function all(): MailingListCollection
    {
        return $this->commandBus->handle(new GetMailingLists());
    }

    public function get(string $listId): MailingList
    {
        return $this->commandBus->handle(new GetMailingList($listId));
    }

    public function create(MailingList $mailingList): void
    {
        $this->commandBus->handle(new CreateMailingList($mailingList));
    }

    public function update(MailingList $mailingList): void
    {
        $this->commandBus->handle(new UpdateMailingList($mailingList));
    }

    public function delete(string $listId): void
    {
        $this->commandBus->handle(new DeleteMailingList($listId));
    }

    public function empty(string $listId): void
    {
        $this->commandBus->handle(new PurgeMailingListSubscriptions($listId));
    }

    public function fill(
        string $listId,
        array $subscriptions,
        int $flags = BulkMailingListSubscriptions::UPDATE
    ): SubscriptionsReport {
        return $this->commandBus->handle(
            new BulkMailingListSubscriptions($listId, $subscriptions, $flags)
        );
    }
}
