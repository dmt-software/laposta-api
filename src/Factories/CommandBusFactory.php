<?php

namespace DMT\Laposta\Api\Factories;

use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Handlers\Locator\HttpRequestLocator;
use DMT\Laposta\Api\Interfaces\Factory;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Plugins\LockingMiddleware;

class CommandBusFactory implements Factory
{
    public static function create(Config $config): CommandBus
    {
        $handlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new HttpRequestLocator(
                RequestHandlerFactory::create($config),
                RequestFactoryFactory::create($config),
                SerializerFactory::create($config)
            ),
            new HandleInflector()
        );

        return new CommandBus([
            new LockingMiddleware(),
            new ValidationMiddleware(),
            $handlerMiddleware
        ]);
    }
}