<?php

namespace DMT\Laposta\Api\Commands\Subscribers;

use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Interfaces\PostRequest;

class UpdateSubscriber implements PostRequest, DeserializableResponse
{
    private Subscriber $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function getUri(): string
    {
        if (!$this->subscriber->id) {
            if (!preg_match('~^(?<user>.+)@(?<domain>[^@]+)$~', $this->subscriber->email, $m)) {
                throw new ValidationException('Invalid identifier used');
            }

            /** double encode + sign */
            $email = sprintf('%s@%s', urlencode(str_replace('+', '%2B', $m['user'])), $m['domain']);
        }

        return sprintf('https://api.laposta.nl/v2/member/%s', $email ?? $this->subscriber->id);
    }

    public function getPayload(): object
    {
        return $this->subscriber;
    }

    public function toEntity(): string
    {
        return Subscriber::class;
    }
}
