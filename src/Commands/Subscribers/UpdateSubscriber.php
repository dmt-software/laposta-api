<?php

namespace DMT\Laposta\Api\Commands\Subscribers;

use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Interfaces\PostRequest;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

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
            if (!preg_match('~^(?<user>.+)@(?<domain>[^@]+)$~', $this->subscriber->email ?? '', $m)) {
                throw new ValidationException(
                    sprintf('Invalid command %s given', self::class), 0, null, new ConstraintViolationList([
                        new ConstraintViolation(
                            'Missing subscriber identifier (id or email)',
                            null,
                            [],
                            '',
                            'subscriber.identifier',
                            $this->subscriber->id
                        )
                    ])
                );
            }

            /** double encode + sign */
            $email = sprintf('%s@%s', urlencode(str_replace('+', '%2B', $m['user'])), $m['domain']);
        }

        return sprintf('https://api.laposta.nl/v2/member/%s', $email ?? $this->subscriber->id);
    }

    public function getPayload(): object
    {
        if (!$this->subscriber->listId) {
            throw new ValidationException(
                sprintf('Invalid command %s given', self::class), 0, null, new ConstraintViolationList([
                    new ConstraintViolation(
                        'Value can not be empty',
                        null,
                        [],
                        '',
                        'subscriber.listId',
                        $this->subscriber->listId
                    )
                ])
            );
        }

        return $this->subscriber;
    }

    public function toEntity(): string
    {
        return Subscriber::class;
    }
}
