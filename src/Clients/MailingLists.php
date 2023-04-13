<?php

namespace DMT\Laposta\Api\Clients;

use DMT\Laposta\Api\Commands\MailingLists\BulkMailingListSubscriptions;
use DMT\Laposta\Api\Commands\MailingLists\CreateMailingList;
use DMT\Laposta\Api\Commands\MailingLists\DeleteMailingList;
use DMT\Laposta\Api\Commands\MailingLists\PurgeMailingListSubscriptions;
use DMT\Laposta\Api\Commands\MailingLists\GetMailingList;
use DMT\Laposta\Api\Commands\MailingLists\GetMailingLists;
use DMT\Laposta\Api\Commands\MailingLists\UpdateMailingList;
use DMT\Laposta\Api\Entity\MailingList;
use DMT\Laposta\Api\Entity\MailingListCollection;
use DMT\Laposta\Api\Entity\SubscriptionsReport;
use DMT\Laposta\Api\Entity\Subscriber;
use League\Tactician\CommandBus;

class MailingLists
{
    public const BULK_INSERT = BulkMailingListSubscriptions::INSERT;
    public const BULK_UPDATE = BulkMailingListSubscriptions::UPDATE;

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * Requesting all lists
     *
     * @return MailingListCollection
     */
    public function all(): MailingListCollection
    {
        return $this->commandBus->handle(new GetMailingLists());
    }

    /**
     * Requesting a list
     *
     * @param string $listId The list's ID
     *
     * @return MailingList
     */
    public function get(string $listId): MailingList
    {
        return $this->commandBus->handle(new GetMailingList($listId));
    }

    /**
     * Adding a list
     *
     * @param MailingList $mailingList The mailing list to add
     */
    public function create(MailingList $mailingList): void
    {
        $this->commandBus->handle(new CreateMailingList($mailingList));
    }

    /**
     * Modifying lists
     *
     * @param MailingList $mailingList The modified mailing list
     */
    public function update(MailingList $mailingList): void
    {
        $this->commandBus->handle(new UpdateMailingList($mailingList));
    }

    /**
     * Deleting a list
     *
     * @param string $listId The ID of the list
     */
    public function delete(string $listId): void
    {
        $this->commandBus->handle(new DeleteMailingList($listId));
    }

    /**
     * Purging a list
     *
     * @param string $listId The ID of the list
     */
    public function purge(string $listId): void
    {
        $this->commandBus->handle(new PurgeMailingListSubscriptions($listId));
    }

    /**
     * Filling / editing a list (in bulk)
     *
     * @param string $listId The ID of the list
     * @param array<Subscriber> $subscriptions An array with member objects. A minimum of 1, maximum of 100,000
     * @param int $flags Bitwise BULK_* constants that represent the bulk mode (add, edit or both).
     *
     * @return SubscriptionsReport
     */
    public function bulk(string $listId, array $subscriptions, int $flags = self::BULK_UPDATE): SubscriptionsReport
    {
        return $this->commandBus->handle(new BulkMailingListSubscriptions($listId, $subscriptions, $flags));
    }
}
