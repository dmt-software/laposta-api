<?php

namespace DMT\Laposta\Api\Serializer;

use DMT\Laposta\Api\Entity\Option;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class FieldOptionsHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        yield  [
            'type' => Option::class,
            'format' => 'http-post',
            'method' => 'optionToArray',
            'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
        ];
    }

    public function optionToArray(
        SerializationVisitorInterface $visitor,
        Option $option,
        array $type,
        Context $context
    ) {;
        return $visitor->visitArray([$option->id => $option->value], ['name' => 'array', 'params' => []]);
    }
}
