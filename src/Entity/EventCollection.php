<?php

namespace DMT\Laposta\Api\Entity;

use ArrayIterator;
use DateTimeInterface;
use DMT\Laposta\Api\Interfaces\Collection;
use JMS\Serializer\Annotation as JMS;
use Traversable;

class EventCollection implements Collection
{
    /**
     * @JMS\Type("array<DMT\Laposta\Api\Entity\Event>")
     * @JMS\SerializedName("data")
     *
     * @var array<\DMT\Laposta\Api\Entity\Event>
     */
    private array $events = [];

    /**
     * The time at which this request was sent
     *
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    public ?DateTimeInterface $dateRequested;

    public function __construct(array $events = [])
    {
        $this->events = $events;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->events);
    }

    public function count(): int
    {
        return count($this->events);
    }
}
