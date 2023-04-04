<?php

namespace DMT\Laposta\Api\Entity;

use DMT\Laposta\Api\Interfaces\Collection;
use JMS\Serializer\Annotation as JMS;

class SubscriberCollection implements Collection
{
    /**
     * @JMS\Type("array<DMT\Laposta\Api\Entity\Subscriber>")
     * @JMS\SerializedName("data")
     *
     * @var array<\DMT\Laposta\Api\Entity\Subscriber>
     */
    public array $subscribers = [];
}
