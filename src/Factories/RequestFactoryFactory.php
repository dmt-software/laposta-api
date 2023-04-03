<?php

namespace DMT\Laposta\Api\Factories;

use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Interfaces\Factory;
use InvalidArgumentException;
use Psr\Http\Message\RequestFactoryInterface;

class RequestFactoryFactory implements Factory
{
    public static function create(Config $config): RequestFactoryInterface
    {
        if ($config->requestFactory instanceof RequestFactoryInterface) {
            return $config->requestFactory;
        }

        $factory = null;
        if (class_exists($config->requestFactory)) {
            $factory = (new \ReflectionClass($config->requestFactory))->newInstanceWithoutConstructor();
        } elseif (is_callable($config->requestFactory)) {
            $factory = call_user_func($config->requestFactory);
        }

        if (!$factory instanceof RequestFactoryInterface) {
            throw new InvalidArgumentException('No valid request factory found in config');
        }

        return $factory;
    }
}
