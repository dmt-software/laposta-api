<?php

namespace DMT\Laposta\Api\Commands\MailingLists;

use DMT\Laposta\Api\Interfaces\DeleteRequest;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteMailingList implements DeleteRequest
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
        return sprintf('https://api.laposta.nl/v2/list/%s', $this->listId);
    }

    public function getQueryString(): string
    {
        return '';
    }
}
