<?php

namespace DMT\Laposta\Api\Entity;

use ArrayIterator;
use DMT\Laposta\Api\Interfaces\Collection;
use JMS\Serializer\Annotation as JMS;
use Traversable;

class WebhookCollection implements Collection
{
    /** @var array<Webhook> */
    #[JMS\Type('array<DMT\Laposta\Api\Entity\Webhook>')]
    #[JMS\SerializedName('data')]
    private array $webhooks = [];

    public function __construct(array $webhooks = [])
    {
        $this->webhooks = $webhooks;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->webhooks);
    }

    public function count(): int
    {
        return count($this->webhooks);
    }
}
