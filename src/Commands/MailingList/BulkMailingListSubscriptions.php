<?php

namespace DMT\Laposta\Api\Commands\MailingList;

use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Entity\BulkReport;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Interfaces\SerializableRequest;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class BulkMailingListSubscriptions implements SerializableRequest, DeserializableResponse
{
    public const INSERT = 1;
    public const UPDATE = 2;

    /**
     * @Assert\NotBlank()
     * @JMS\Exclude()
     */
    private string $listId;

    /**
     * @Assert\Count(min=1, max=100000)
     * @JMS\Type("array<DMT\Laposta\Api\Entity\Subscriber>")
     * @JMS\SerializedName("members")
     *
     * @var array<Subscriber>
     */
    private array $subscribers;

    /**
     * @JMS\Exclude()
     */
    private int $flags;

    /**
     * @param string $listId
     * @param array $subscribers
     * @param int $mode
     */
    public function __construct(string $listId, array $subscribers, int $mode = self::UPDATE)
    {
        $this->listId = $listId;
        $this->subscribers = $subscribers;
        $this->flags = $mode;
    }

    public function getUri(): string
    {
        return sprintf('https://api.laposta.nl/v2/list/%s/members', $this->listId);
    }

    /**
     * @JMS\VirtualProperty()
     */
    public function getMode(): string
    {
        $mode = '';
        if ($this->flags & self::INSERT) {
            $mode .= 'add';
        }

        if ($this->flags & self::UPDATE) {
            $mode .= $mode ? '_and_edit' : 'edit';
        }

        return $mode;
    }

    public function toEntity(): string
    {
        return BulkReport::class;
    }
}
