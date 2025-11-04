<?php

namespace DMT\Laposta\Api\Entity;

use DateTimeInterface;
use JMS\Serializer\Annotation as JMS;

class EventInformation
{
    /**
     * Subscribe options that can be processed differently by a webhook event processor.
     */
    public const string SUBSCRIBE_ACTION_SUBSCRIBED = 'subscribed';
    public const string SUBSCRIBE_ACTION_RESUBSCRIBED = 'resubscribed';

    /**
     * Deactivate options that can be processed differently by a webhook event processor.
     */
    public const string DEACTIVATE_ACTION_UNSUBSCRIBED = 'unsubscribed';
    public const string DEACTIVATE_ACTION_DELETED = 'deleted';
    public const string DEACTIVATE_ACTION_HARDBOUNCE = 'hardbounce';

    /** Source of the event: could be app (within the web interface) or external */
    #[JMS\Type('string')]
    public null|string $source = null;

    /** Extra information regarding the event */
    #[JMS\Type('string')]
    public null|string $action = null;

    /** The moment this event occurred */
    #[JMS\Type("DateTime<'Y-m-d H:i:s'>")]
    public null|DateTimeInterface $dateEvent = null;
}
