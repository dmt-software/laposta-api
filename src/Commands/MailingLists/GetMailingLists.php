<?php

namespace DMT\Laposta\Api\Commands\MailingLists;

use DMT\Laposta\Api\Entity\MailingListCollection;
use DMT\Laposta\Api\Interfaces\GetRequest;

class GetMailingLists implements GetRequest
{
    public function getUri(): string
    {
        return 'https://api.laposta.nl/v2/list';
    }

    public function getQueryString(): string
    {
        return '';
    }

    public function toEntity(): string
    {
        return MailingListCollection::class;
    }
}
