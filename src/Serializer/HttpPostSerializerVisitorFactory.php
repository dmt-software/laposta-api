<?php

namespace DMT\Laposta\Api\Serializer;

use JMS\Serializer\Visitor\Factory\SerializationVisitorFactory;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class HttpPostSerializerVisitorFactory implements SerializationVisitorFactory
{
    public const PHP_QUERY_RFC1738 = PHP_QUERY_RFC1738;
    public const PHP_QUERY_RFC3986 = PHP_QUERY_RFC3986;

    private int $encodingType = self::PHP_QUERY_RFC1738;

    public function getVisitor(): SerializationVisitorInterface
    {
        return new HttpPostSerializationVisitor($this->encodingType);
    }

    public function setEncodingType(int $encodingType = self::PHP_QUERY_RFC1738): self
    {
        $this->encodingType = $encodingType;

        return $this;
    }
}
