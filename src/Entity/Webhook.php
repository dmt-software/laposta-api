<?php

namespace DMT\Laposta\Api\Entity;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class Webhook
{
    public const EVENT_SUBSCRIBED = 'subscribed';
    public const EVENT_MODIFIED = 'modified';
    public const EVENT_DEACTIVATED = 'deactivated';

    /**
     * The ID of this webhook
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("webhook_id")
     */
    public ?string $id = null;

    /**
     * The ID of the list to which the field belongs
     *
     * @JMS\Type("string")
     * @Assert\NotBlank()
     */
    public string $listId;

    /**
     * Date and time of creation
     *
     * @JMS\Groups({"System"})
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    public ?DateTime $created = null;

    /**
     * Date and time of last modification made
     *
     * @JMS\Groups({"System"})
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    public ?DateTime $modified = null;

    /**
     * The status of this webhook: active or deleted
     *
     * @JMS\Groups({"System"})
     * @JMS\Type("string")
     */
    public string $state = 'active';

    /**
     * When will the webhook be requested? (subscribed, modified or deactivated)
     *
     * @JMS\Type("string")
     * @Assert\NotBlank()
     * @Assert\Choice({"subscribed", "modified", "deactivated"})
     */
    public ?string $event = null;

    /**
     * The URL to be accessed
     *
     * @JMS\Type("string")
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    public ?string $url = null;

    /**
     * Is the accessing of the webhook (temporarily) blocked?
     *
     * @JMS\Type("bool")
     */
    public bool $blocked = false;
}
