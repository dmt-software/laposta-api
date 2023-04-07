<?php

namespace DMT\Laposta\Api\Commands\MailingList;

use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Entity\MailingList;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Interfaces\PostRequest;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class UpdateMailingList implements PostRequest, DeserializableResponse
{
    private MailingList $mailingList;

    public function __construct(MailingList $mailingList)
    {
        $this->mailingList = $mailingList;
    }

    public function getUri(): string
    {
        if (!$this->mailingList->id) {
            throw new ValidationException(
                sprintf('Invalid command %s given', self::class), 0, null, new ConstraintViolationList([
                    new ConstraintViolation(
                        'Value can not be empty',
                        null,
                        [],
                        '',
                        'mailingList.id',
                        $this->mailingList->id
                    )
                ])
            );
        }

        return sprintf('https://api.laposta.nl/v2/field/%s', $this->mailingList->id);
    }

    public function getPayload(): object
    {
        return $this->mailingList;
    }

    public function toEntity(): string
    {
        return MailingList::class;
    }
}
