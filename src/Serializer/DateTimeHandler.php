<?php

namespace DMT\Laposta\Api\Serializer;

use DateTimeInterface;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class DateTimeHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods(): iterable
    {
        yield [
            'type' => 'DateTime',
            'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
            'format' => 'http-post',
            'method' => 'serializeDateTimeInterface',
        ];
    }

    public function serializeDateTimeInterface(
        SerializationVisitorInterface $visitor,
        DateTimeInterface $date,
        array $type,
        SerializationContext $context
    ) {
        $format = $type['params'][0] ?? 'Y-m-d H:i:s';

        if ('U' === $format) {
            return $visitor->visitInteger((int) $date->format($format), $type);
        }

        return $visitor->visitString($date->format($format), $type);
    }
}
