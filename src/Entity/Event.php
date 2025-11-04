<?php

namespace DMT\Laposta\Api\Entity;

use DateTimeInterface;
use JMS\Serializer\Annotation as JMS;

class Event
{
    public const string EVENT_SUBSCRIBED = Webhook::EVENT_SUBSCRIBED;
    public const string EVENT_MODIFIED = Webhook::EVENT_MODIFIED;
    public const string EVENT_DEACTIVATED = Webhook::EVENT_DEACTIVATED;

    /** The type of webhook (always member) */
    #[JMS\Type('string')]
    public string $type = 'member';

    /** The reason why the webhook has been requested. */
    #[JMS\Type('string')]
    public null|string $event = null;

    /** The data of the object that has been added, modified or deleted. */
    #[JMS\Type(Subscriber::class)]
    #[JMS\SerializedName('data')]
    public null|Subscriber $subscriber = null;

    /** Extra information regarding the request of the webhook. */
    #[JMS\Type(EventInformation::class)]
    public null|EventInformation $info = null;

    /** The time at which this request was sent */
    #[JMS\Type("DateTime<'Y-m-d H:i:s'>")]
    public null|DateTimeInterface $dateFired;
}
