<?php

namespace DMT\Laposta\Api\Factories;

use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Interfaces\Factory;
use DMT\Laposta\Api\Serializer\CustomFieldsEventSubscriber;
use DMT\Laposta\Api\Serializer\DateTimeHandler;
use DMT\Laposta\Api\Serializer\ExistingObjectConstructor;
use DMT\Laposta\Api\Serializer\FieldOptionsEventSubscriber;
use DMT\Laposta\Api\Serializer\FieldOptionsHandler;
use DMT\Laposta\Api\Serializer\HttpPostSerializerVisitorFactory;
use DMT\Laposta\Api\Serializer\NormalizeNestedEntityEventSubscriber;
use DMT\Laposta\Api\Serializer\SubscribeOptionsEventSubscriber;
use JMS\Serializer\Construction\UnserializeObjectConstructor;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

class SerializerFactory implements Factory
{
    public static function create(Config $config): Serializer
    {
        return SerializerBuilder::create()
            ->addDefaultDeserializationVisitors()
            ->addDefaultSerializationVisitors()
            ->setSerializationVisitor('http-post', new HttpPostSerializerVisitorFactory())
            ->addDefaultListeners()
            ->configureListeners(function (EventDispatcherInterface $dispatcher) use ($config): void {
                $dispatcher->addSubscriber(new FieldOptionsEventSubscriber());
                $dispatcher->addSubscriber(new NormalizeNestedEntityEventSubscriber());
                $dispatcher->addSubscriber(new CustomFieldsEventSubscriber($config));
                $dispatcher->addSubscriber(new SubscribeOptionsEventSubscriber());
            })
            ->addDefaultHandlers()
            ->configureHandlers(function (HandlerRegistryInterface $registry): void {
                $registry->registerSubscribingHandler(new FieldOptionsHandler());
                $registry->registerSubscribingHandler(new DateTimeHandler());
            })
            ->setObjectConstructor(new ExistingObjectConstructor(new UnserializeObjectConstructor()))
            ->build();
    }
}
