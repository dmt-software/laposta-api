<?php

namespace DMT\Laposta\Api\Commands\Subscribers;

use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Interfaces\PostRequest;
use Symfony\Component\Validator\Constraints as Assert;

class CreateSubscriber implements PostRequest, DeserializableResponse
{
    public const SUPPRESS_EMAIL_NOTIFICATION = 1;
    public const SUPPRESS_EMAIL_WELCOME = 2;
    public const IGNORE_DOUBLEOPTIN = 4;

    /**
     * @Assert\Valid()
     */
    private Subscriber $subscriber;
    private int $subscribeOptions;

    public function __construct(Subscriber $subscriber, int $flags = 0)
    {
        $this->subscriber = $subscriber;
        $this->subscribeOptions = $flags;
    }

    public function getUri(): string
    {
        return 'https://api.laposta.nl/v2/member';
    }

    public function getPayload(): object
    {
        $this->subscriber->id = null;

        return $this->subscriber;
    }

    public function toEntity(): string
    {
        return Subscriber::class;
    }

    public function getSubscribeOptions(): array
    {
        $options = [];
        if (self::SUPPRESS_EMAIL_NOTIFICATION & $this->subscribeOptions) {
            $options['suppress_email_notification'] = true;
        }
        if (self::SUPPRESS_EMAIL_WELCOME & $this->subscribeOptions) {
            $options['suppress_email_welcome'] = true;
        }
        if (self::IGNORE_DOUBLEOPTIN & $this->subscribeOptions) {
            $options['ignore_doubleoptin'] = true;
        }

        return $options;
    }
}
