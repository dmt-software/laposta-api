<?php

namespace DMT\Laposta\Api\Commands\Subscribers;

use DMT\Laposta\Api\Entity\SubscriberCollection;
use DMT\Laposta\Api\Interfaces\Collection;
use DMT\Laposta\Api\Interfaces\GetRequest;
use Symfony\Component\Validator\Constraints as Assert;

class GetSubscribers implements GetRequest, Collection
{
    /**
     * @Assert\NotBlank()
     */
    private string $listId;

    /**
     * @Assert\Choice({"active", "unsubscribed", "cleaned"})
     */
    private ?string $state;

    public function __construct(string $listId, ?string $state)
    {
        $this->listId = $listId;
        $this->state = $state;
    }

    public function getUri(): string
    {
        return 'https://api.laposta.nl/v2/member';
    }

    public function getQueryString(): ?string
    {
        return http_build_query(
            array_filter([
                'list_id' => $this->listId,
                'state' => $this->state
            ])
        );
    }

    public function toEntity(): string
    {
        return SubscriberCollection::class;
    }
}
