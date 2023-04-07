<?php

namespace DMT\Laposta\Api\Entity;

use ArrayIterator;
use DMT\Laposta\Api\Interfaces\Collection;
use JMS\Serializer\Annotation as JMS;
use Traversable;

class MailingListCollection implements Collection
{
    /**
     * @JMS\Type("array<DMT\Laposta\Api\Entity\MailingList>")
     * @JMS\SerializedName("data")
     *
     * @var array<\DMT\Laposta\Api\Entity\MailingList>
     */
    private array $mailingLists = [];

    public function __construct(array $mailingLists = [])
    {
        $this->mailingLists = $mailingLists;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->mailingLists);
    }

    public function count()
    {
        return count($this->mailingLists);
    }
}
