<?php

namespace DMT\Laposta\Api\Entity;

use DateTimeInterface;
use JMS\Serializer\Annotation as JMS;

class EventInformation
{
    /**
     * Subscribe options that can be processed differently by a webhook event processor.
     */
    public const SUBSCRIBE_ACTION_SUBSCRIBED = 'subscribed';
    public const SUBSCRIBE_ACTION_RESUBSCRIBED = 'resubscribed';

    /**
     * Deactivate options that can be processed differently by a webhook event processor.
     */
    public const DEACTIVATE_ACTION_UNSUBSCRIBED = 'unsubscribed';
    public const DEACTIVATE_ACTION_DELETED = 'deleted';
    public const DEACTIVATE_ACTION_HARDBOUNCE = 'hardbounce';

    /**
     * Source of the event: could be app (within the web interface) or external
     *
     * @JMS\Type("string")
     */
    public ?string $source = null;

    /**
     * Extra information regarding the event
     *
     * @JMS\Type("string")
     */
    public ?string $action = null;

    /**
     * The moment this event occurred
     *
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    public ?DateTimeInterface $dateEvent = null;
}
