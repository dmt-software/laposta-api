<?php

namespace DMT\Laposta\Api\Entity;

use DateTimeInterface;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class Subscriber
{
    /** The ID of this member object */
    #[JMS\Type('string')]
    #[JMS\SerializedName('member_id')]
    public null|string $id = null;

    /** The ID of the related list */
    #[Assert\NotBlank]
    #[JMS\Type('string')]
    public string $listId;

    /** The email address */
    #[Assert\Email]
    #[Assert\NotBlank]
    #[JMS\Type('string')]
    public string $email;

    /** The current status of this subscriber: active, unsubscribed, unconfirmed or cleaned */
    #[JMS\Type('string')]
    public null|string $state = null;

    /** Date and time of subscription, format YYYY-MM-DD HH:MM:SS */
    #[JMS\Groups(['System'])]
    #[JMS\Type("DateTime<'Y-m-d H:i:s'>")]
    public null|DateTimeInterface $signupDate = null;

    /** Date and time of last modification made, format YYYY-MM-DD HH:MM:SS */
    #[JMS\Groups(['System'])]
    #[JMS\Type("DateTime<'Y-m-d H:i:s'>")]
    public null|DateTimeInterface $modified = null;

    /** IP from which the subscriber is registered */
    #[JMS\Type('string')]
    public null|string $ip = null;

    /** URL from which the subscriber is registered */
    #[JMS\Type('string')]
    public null|string $sourceUrl = null;

    /** An object with the value of all additional fields of the corresponding list */
    #[Assert\Valid]
    #[JMS\Type(BaseCustomFields::class)]
    public BaseCustomFields $customFields;
}
