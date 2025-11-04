<?php

namespace DMT\Laposta\Api\Entity;

use ArrayIterator;
use DMT\Laposta\Api\Interfaces\Collection;
use JMS\Serializer\Annotation as JMS;
use Traversable;

class SubscriberCollection implements Collection
{
    /** @var array<Subscriber> */
    #[JMS\Type('array<DMT\Laposta\Api\Entity\Subscriber>')]
    #[JMS\SerializedName('data')]
    private array $subscribers = [];

    public function __construct(array $subscribers = [])
    {
        $this->subscribers = $subscribers;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->subscribers);
    }

    public function count(): int
    {
        return count($this->subscribers);
    }
}
