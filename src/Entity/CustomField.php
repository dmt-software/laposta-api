<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

class CustomField
{
    #[JMS\Type('string')]
    public string $name;

    #[JMS\Type('string')]
    public string|array $value;
}
