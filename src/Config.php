<?php

namespace DMT\Laposta\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;

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

    /**
     * @var \Psr\Http\Client\ClientInterface|string|callable
     */
    public $httpClient = Client::class;

    /**
     * @var \Psr\Http\Message\RequestFactoryInterface|string|callable
     */
    public $requestFactory = HttpFactory::class;


    public static function fromArray(array $configArray): self
    {
        $config = new self();
        foreach ($configArray as $key => $value) {
            if (property_exists($config, $key)) {
                $config->$key = $value;
            }
        }
        return $config;
    }
}
