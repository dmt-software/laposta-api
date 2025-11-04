<?php

namespace DMT\Laposta\Api\Entity;

use ArrayIterator;
use DMT\Laposta\Api\Interfaces\Collection;
use JMS\Serializer\Annotation as JMS;
use Traversable;

class MailingListCollection implements Collection
{
    /** @var array<\DMT\Laposta\Api\Entity\MailingList> */
    #[JMS\Type('array<DMT\Laposta\Api\Entity\MailingList>')]
    #[JMS\SerializedName('data')]
    private array $mailingLists = [];

    public function __construct(array $mailingLists = [])
    {
        $this->mailingLists = $mailingLists;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->mailingLists);
    }

    public function count(): int
    {
        return count($this->mailingLists);
    }
}
