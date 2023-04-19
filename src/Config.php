<?php

namespace DMT\Laposta\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;

class Config
{
    /**
     * The API-key for connecting to Laposta
     */
    public string $apiKey;

    /**
     * A mapping between list_id and member entity implementation.
     *
     * @var array<string, string>
     */
    public array $customFieldsClasses = [];

    /**
     * The http client to use can be a class name, instance or callable
     * that returns the client class
     *
     * @var \Psr\Http\Client\ClientInterface|string|callable
     */
    public $httpClient = Client::class;

    /**
     * The request factory to use can be a class name, instance or callable
     * that returns the implementing factory class
     *
     * @var \Psr\Http\Message\RequestFactoryInterface|string|callable
     */
    public $requestFactory = HttpFactory::class;

    /**
     * Load the configuration from a file.
     *
     * This file can return a simple config array, a callable that returns a Config instance, a Config instance itself
     * or contains a $config variable that hold the Config instance.
     */
    public static function load(string $bootstrap): ?self
    {
        $config = null;

        if (is_file($bootstrap)) {
            $contents = @include($bootstrap);
            if ($contents instanceof Config) {
                $config = $contents;
            } elseif (is_callable($contents)) {
                $config = call_user_func($contents);
            } elseif (is_array($contents)) {
                $config = Config::fromArray($contents);
            }
        }

        return $config instanceof self ? $config : null;
    }

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
