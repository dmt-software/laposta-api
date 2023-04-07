<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

class Subscriptions
{
    /**
     * @JMS\Type("int")
     */
    public int $active = 0;

    /**
     * @JMS\Type("int")
     */
    public int $unsubscribed = 0;

    /**
     * @JMS\Type("int")
     */
    public int $cleaned = 0;
}
