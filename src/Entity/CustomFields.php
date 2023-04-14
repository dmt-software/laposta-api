<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

class CustomFields extends BaseCustomFields
{
    /**
     * @JMS\Type("array<DMT\Laposta\Api\Entity\CustomField>")
     * @JMS\Inline()
     *
     * @var null|array<\DMT\Laposta\Api\Entity\CustomField>
     */
    public ?array $field = null;
}