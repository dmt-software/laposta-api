<?php

namespace DMT\Laposta\Api\Clients;

use DMT\Laposta\Api\Commands\GetSubscriber;
use DMT\Laposta\Api\Commands\GetSubscribers;
use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Entity\SubscriberCollection;
use League\Tactician\CommandBus;

class Subscribers
{
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
}
