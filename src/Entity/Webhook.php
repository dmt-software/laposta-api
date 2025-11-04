<?php

namespace DMT\Laposta\Api\Entity;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class Webhook
{
    public const string EVENT_SUBSCRIBED = 'subscribed';
    public const string EVENT_MODIFIED = 'modified';
    public const string EVENT_DEACTIVATED = 'deactivated';

    /**
     * The ID of this webhook
     */
    #[JMS\Type('string')]
    #[JMS\SerializedName('webhook_id')]
    public null|string $id = null;

    /**
     * The ID of the list to which the field belongs
     */
    #[Assert\NotBlank]
    #[JMS\Type('string')]
    public string $listId;

    /**
     * Date and time of creation
     */
    #[JMS\Groups(['System'])]
    #[JMS\Type("DateTime<'Y-m-d H:i:s'>")]
    public null|DateTime $created = null;

    /**
     * Date and time of last modification made
     */
    #[JMS\Groups(['System'])]
    #[JMS\Type("DateTime<'Y-m-d H:i:s'>")]
    public null|DateTime $modified = null;

    /**
     * The status of this webhook: active or deleted
     */
    #[JMS\Groups(['System'])]
    #[JMS\Type('string')]
    public string $state = 'active';

    /**
     * When will the webhook be requested? (subscribed, modified or deactivated)
     */
    #[Assert\NotBlank]
    #[Assert\Choice([self::EVENT_SUBSCRIBED, self::EVENT_MODIFIED, self::EVENT_DEACTIVATED])]
    #[JMS\Type('string')]
    public null|string $event = null;

    /**
     * The URL to be accessed
     */
    #[Assert\NotBlank]
    #[Assert\Url]
    #[JMS\Type('string')]
    public null|string $url = null;

    /**
     * Is the accessing of the webhook (temporarily) blocked?
     */
    #[JMS\Type('bool')]
    public bool $blocked = false;
}
