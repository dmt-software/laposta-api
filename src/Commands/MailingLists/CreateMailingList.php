<?php

namespace DMT\Laposta\Api\Commands\MailingLists;

use DMT\Laposta\Api\Entity\MailingList;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Interfaces\PostRequest;
use Symfony\Component\Validator\Constraints as Assert;

class CreateMailingList implements PostRequest, DeserializableResponse
{
    #[Assert\Valid]
    private MailingList $mailingList;

    public function __construct(MailingList $mailingList)
    {
        $this->mailingList = $mailingList;
    }

    public function getUri(): string
    {
        return 'https://api.laposta.nl/v2/list';
    }

    public function getPayload(): object
    {
        $this->mailingList->id = null;

        return $this->mailingList;
    }

    public function toEntity(): string
    {
        return MailingList::class;
    }
}
