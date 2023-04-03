<?php

namespace DMT\Laposta\Api\Factories;

use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Interfaces\Factory;
use InvalidArgumentException;
use Psr\Http\Client\ClientInterface;

class HttpClientFactory implements Factory
{
    public static function create(Config $config): ClientInterface
    {
        if ($config->httpClient instanceof ClientInterface) {
            return $config->httpClient;
        }

        $client = null;
        if (class_exists($config->httpClient)) {
            $client = (new \ReflectionClass($config->httpClient))->newInstance();
        } elseif (is_callable($config->httpClient)) {
            $client = call_user_func($config->httpClient);
        }

        if (!$client instanceof ClientInterface) {
            throw new InvalidArgumentException('No valid http client found in config');
        }

        return $client;
    }
}
