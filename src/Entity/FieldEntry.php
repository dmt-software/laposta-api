<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

class FieldEntry
{
    /**
     * @JMS\Type("DMT\Laposta\Api\Entity\Field")
     */
    public $field;
}
