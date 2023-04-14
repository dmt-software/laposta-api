<?php

namespace DMT\Laposta\Api\Entity;

use DateTimeInterface;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class Subscriber
{
    /**
     * The ID of this member object
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("member_id")
     */
    public ?string $id = null;

    /**
     * The ID of the related list
     *
     * @JMS\Type("string")
     * @Assert\NotBlank()
     */
    public string $listId;

    /**
     * The email address
     *
     * @JMS\Type("string")
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    public string $email;

    /**
     * The current status of this subscriber: active, unsubscribed, unconfirmed or cleaned
     *
     * @JMS\Type("string")
     */
    public ?string $state = null;

    /**
     * Date and time of subscription, format YYYY-MM-DD HH:MM:SS
     *
     * @JMS\Groups({"System"})
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    public ?DateTimeInterface $signupDate = null;

    /**
     * Date and time of last modification made, format YYYY-MM-DD HH:MM:SS
     *
     * @JMS\Groups({"System"})
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    public ?DateTimeInterface $modified = null;

    /**
     * IP from which the subscriber is registered
     *
     * @JMS\Type("string")
     */
    public ?string $ip = null;

    /**
     * URL from which the subscriber is registered
     *
     * @JMS\Type("string")
     */
    public ?string $sourceUrl = null;

    /**
     * An object with the value of all additional fields of the corresponding list
     *
     * @JMS\Type("DMT\Laposta\Api\Entity\BaseCustomFields")
     * @Assert\Valid()
     */
    public BaseCustomFields $customFields;
}
