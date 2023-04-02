<?php

namespace DMT\Laposta\Api;

class Config
{
    /**
     * The client api key
     */
    public string $apiKey;

    /**
     * A mapping between list_id and member entity implementation.
     *
     * @var array<string, string>
     */
    public array $customFieldsClasses = [];
}
