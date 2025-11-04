<?php

namespace DMT\Laposta\Api\Commands\Subscribers;

use DMT\Laposta\Api\Interfaces\DeleteRequest;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteSubscriber implements DeleteRequest
{
    #[Assert\NotBlank]
    private string $listId;

    #[Assert\NotBlank]
    private string $identifiedBy;

    public function __construct(string $listId, string $identifier)
    {
        $this->listId = $listId;
        $this->identifiedBy = $identifier;
    }

    public function getUri(): string
    {
        if (preg_match('~^(?<user>.+)@(?<domain>[^@]+)$~', $this->identifiedBy, $m)) {
            /** double encode + sign */
            $email = sprintf('%s@%s', urlencode(str_replace('+', '%2B', $m['user'])), $m['domain']);
        }

        return sprintf('https://api.laposta.nl/v2/member/%s', $email ?? $this->identifiedBy);
    }

    public function getQueryString(): string
    {
        return http_build_query(['list_id' => $this->listId]);
    }
}
