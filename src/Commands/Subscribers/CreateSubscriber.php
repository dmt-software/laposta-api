<?php

namespace DMT\Laposta\Api\Commands\Subscribers;

use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Interfaces\PostRequest;

class CreateSubscriber implements PostRequest, DeserializableResponse
{
    private Subscriber $subscriber;
    private $subscribeOptions;

    public function __construct(Subscriber $subscriber, $subscribeOptions = null)
    {
        $this->subscriber = $subscriber;
        $this->subscribeOptions = $subscribeOptions;
    }

    public function getUri(): string
    {
        return 'https://api.laposta.nl/v2/member';
    }

    public function getPayload(): object
    {
        return $this->subscriber;
    }

    public function toEntity(): string
    {
        return Subscriber::class;
    }

    public function getSubscribeOptions()
    {

    }
}
