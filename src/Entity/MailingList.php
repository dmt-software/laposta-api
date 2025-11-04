<?php

namespace DMT\Laposta\Api\Entity;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class MailingList
{
    /** ID of the list in question */
    #[JMS\Type('string')]
    #[JMS\SerializedName('list_id')]
    public null|string $id = null;

    /** Date and time of creation */
    #[JMS\Groups(['System'])]
    #[JMS\Type("DateTime<'Y-m-d H:i:s'>")]
    public null|DateTime $created = null;

    /** Date and time of last modification made */
    #[JMS\Groups(['System'])]
    #[JMS\Type("DateTime<'Y-m-d H:i:s'>")]
    public null|DateTime $modified = null;

    /** Status of the list: active or deleted */
    #[JMS\Groups(['System'])]
    #[JMS\Type('string')]
    public string $state = 'active';

    /** Name given to the list in question */
    #[Assert\NotBlank]
    #[JMS\Type('string')]
    public string $name;

    /** Potential remarks */
    #[JMS\Type('string')]
    public null|string $remarks = null;

    /** Email address to which a notification will be sent upon a subscription */
    #[Assert\Email]
    #[JMS\Type('string')]
    public null|string $subscribeNotificationEmail = null;

    /** Email address to which a notification will be sent upon the cancelling of a subscription */
    #[Assert\Email]
    #[JMS\Type('string')]
    public null|string $unsubscribeNotificationEmail = null;

    /** Information regarding the number of active, unsubscribed and cleaned (deleted) members */
    #[JMS\Groups(['System'])]
    #[JMS\SerializedName('members')]
    #[JMS\Type(Subscriptions::class)]
    public Subscriptions $subscriptions;
}
