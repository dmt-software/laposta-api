<?php

namespace DMT\Laposta\Api\Factories;

use DMT\Http\Client\Middleware\BasicAuthenticationMiddleware;
use DMT\Http\Client\RequestHandler;
use DMT\Http\Client\RequestHandlerInterface;
use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Http\ExceptionMiddleware;
use DMT\Laposta\Api\Interfaces\Factory;

class RequestHandlerFactory implements Factory
{
    public static function create(Config $config): RequestHandlerInterface
    {
        return new RequestHandler(
            HttpClientFactory::create($config),
            new ExceptionMiddleware(),
            new BasicAuthenticationMiddleware($config->apiKey, '')
        );
    }
}
