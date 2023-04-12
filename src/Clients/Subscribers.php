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
    public const SUPPRESS_EMAIL_NOTIFICATION = CreateSubscriber::SUPPRESS_EMAIL_NOTIFICATION;
    public const SUPPRESS_EMAIL_WELCOME = CreateSubscriber::SUPPRESS_EMAIL_WELCOME;
    public const IGNORE_DOUBLEOPTIN = CreateSubscriber::IGNORE_DOUBLEOPTIN;

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function all(string $listId, string $state = null): SubscriberCollection
    {
        return $this->commandBus->handle(new GetSubscribers($listId, $state));
    }

    public function get(string $listId, string $identifiedBy): Subscriber
    {
        return $this->commandBus->handle(new GetSubscriber($listId, $identifiedBy));
    }

    public function create(Subscriber $subscriber, int $flags = 0): void
    {
        $this->commandBus->handle(new CreateSubscriber($subscriber, $flags));
    }

    public function update(Subscriber $subscriber): void
    {
        $this->commandBus->handle(new UpdateSubscriber($subscriber));
    }

    public function delete(string $listId, string $identifiedBy): void
    {
        $this->commandBus->handle(new DeleteSubscriber($listId, $identifiedBy));
    }
}
