<?php

namespace DMT\Laposta\Api\Commands\MailingList;

use DMT\Laposta\Api\Interfaces\DeleteRequest;
use Symfony\Component\Validator\Constraints as Assert;

class EmptyMailingList implements DeleteRequest
{
    /**
     * @Assert\NotBlank()
     */
    private string $listId;

    public function __construct(string $listId)
    {
        $this->listId = $listId;
    }

    public function getUri(): string
    {
        return sprintf('https://api.laposta.nl/v2/list/%s/members', $this->listId);
    }

    public function getQueryString(): string
    {
        return '';
    }
}
