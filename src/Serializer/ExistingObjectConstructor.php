<?php

namespace DMT\Laposta\Api\Serializer;

use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;

class ExistingObjectConstructor implements ObjectConstructorInterface
{
    public const ATTRIBUTE = 'deserialization-target';

    private ObjectConstructorInterface $fallbackConstructor;

    public function __construct(ObjectConstructorInterface $fallbackConstructor)
    {
        $this->fallbackConstructor = $fallbackConstructor;
    }

    public function construct(
        DeserializationVisitorInterface $visitor,
        ClassMetadata $metadata,
        $data,
        array $type,
        DeserializationContext $context
    ): ?object {
        if ($context->hasAttribute(self::ATTRIBUTE)
            && ($context->getAttribute(self::ATTRIBUTE) instanceof $type['name'])
        ) {
            return $context->getAttribute(self::ATTRIBUTE);
        }

        return $this->fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
    }
}
