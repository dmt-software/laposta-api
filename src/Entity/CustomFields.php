<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

class CustomFields extends BaseCustomFields
{
    /** @var null|array<CustomField> */
    #[JMS\Type('array<DMT\Laposta\Api\Entity\CustomField>')]
    #[JMS\Inline]
    public null|array $field = null;
}