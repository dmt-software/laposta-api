<?php

namespace DMT\Laposta\Api\Clients;

use DMT\Laposta\Api\Commands\Subscribers\CreateSubscriber;
use DMT\Laposta\Api\Commands\Subscribers\DeleteSubscriber;
use DMT\Laposta\Api\Commands\Subscribers\GetSubscriber;
use DMT\Laposta\Api\Commands\Subscribers\GetSubscribers;
use DMT\Laposta\Api\Commands\Subscribers\UpdateSubscriber;
use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Entity\SubscriberCollection;
use League\Tactician\CommandBus;

class Subscribers
{
    public const OPTION_SUPPRESS_EMAIL_NOTIFICATION = CreateSubscriber::SUPPRESS_EMAIL_NOTIFICATION;
    public const OPTION_SUPPRESS_EMAIL_WELCOME = CreateSubscriber::SUPPRESS_EMAIL_WELCOME;
    public const OPTION_IGNORE_DOUBLEOPTIN = CreateSubscriber::IGNORE_DOUBLEOPTIN;

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * Requesting all subscribers of a list
     *
     * @param string $listId The ID of the list from which the subscribers are being requested
     * @param string|null $state The status of the requested subscribers: active, unsubscribed or cleaned
     *
     * @return SubscriberCollection
     */
    public function all(string $listId, string $state = null): SubscriberCollection
    {
        return $this->commandBus->handle(new GetSubscribers($listId, $state));
    }

    /**
     * Requesting a subscriber
     *
     * @param string $listId The ID of the list in which the subscriber appears
     * @param string $identifiedBy The ID or the email address of the subscriber
     *
     * @return Subscriber
     */
    public function get(string $listId, string $identifiedBy): Subscriber
    {
        return $this->commandBus->handle(new GetSubscriber($listId, $identifiedBy));
    }

    /**
     * Adding a subscriber
     *
     * @param Subscriber $subscriber The subscriber to add
     * @param int $flags Bitwise OPTION_* constants that represent the subscribe options
     */
    public function create(Subscriber $subscriber, int $flags = 0): void
    {
        $this->commandBus->handle(new CreateSubscriber($subscriber, $flags));
    }

    /**
     * Modifying a subscriber
     *
     * @param Subscriber $subscriber The modified subscriber
     */
    public function update(Subscriber $subscriber): void
    {
        $this->commandBus->handle(new UpdateSubscriber($subscriber));
    }

    /**
     * Deleting a subscriber
     *
     * @param string $listId The ID of the list in which the subscriber appears
     * @param string $identifiedBy The ID or the email address of the subscriber
     */
    public function delete(string $listId, string $identifiedBy): void
    {
        $this->commandBus->handle(new DeleteSubscriber($listId, $identifiedBy));
    }
}
