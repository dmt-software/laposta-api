<?php

namespace DMT\Laposta\Api\Factories;

use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Interfaces\Factory;
use DMT\Laposta\Api\Serializer\CustomFieldsDiscriminatorEventSubscriber;
use DMT\Laposta\Api\Serializer\ExistingObjectConstructor;
use DMT\Laposta\Api\Serializer\FieldOptionsHandler;
use DMT\Laposta\Api\Serializer\HttpPostSerializerVisitorFactory;
use DMT\Laposta\Api\Serializer\NormalizeNestedDataEventSubscriber;
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
            ->setSerializationVisitor('http-post', new HttpPostSerializerVisitorFactory())
            ->addDefaultDeserializationVisitors()
            ->addDefaultListeners()
            ->configureListeners(function (EventDispatcherInterface $dispatcher) use ($config) {
                $dispatcher->addSubscriber(new NormalizeNestedDataEventSubscriber());
                $dispatcher->addSubscriber(new CustomFieldsDiscriminatorEventSubscriber($config));
            })
            ->addDefaultHandlers()
            ->configureHandlers(function (HandlerRegistryInterface $registry) {
                $registry->registerSubscribingHandler(new FieldOptionsHandler());
            })
            ->setObjectConstructor(new ExistingObjectConstructor(new UnserializeObjectConstructor()))
            ->build();
    }
}
