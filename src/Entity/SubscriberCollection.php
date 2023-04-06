<?php

namespace DMT\Laposta\Api\Entity;

use ArrayIterator;
use DMT\Laposta\Api\Interfaces\Collection;
use JMS\Serializer\Annotation as JMS;
use Traversable;

class SubscriberCollection implements Collection
{
    /**
     * @JMS\Type("array<DMT\Laposta\Api\Entity\Subscriber>")
     * @JMS\SerializedName("data")
     *
     * @var array<\DMT\Laposta\Api\Entity\Subscriber>
     */
    private array $subscribers = [];

    public function __construct(array $subscribers = [])
    {
        $this->subscribers = $subscribers;
    }


    public function getIterator()
    {
        return new ArrayIterator($this->subscribers);
    }

    public function count()
    {
        return count($this->subscribers);
    }
}
