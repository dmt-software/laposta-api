<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

class Fields
{
    /**
     * @JMS\Type("array<DMT\Laposta\Api\Entity\FieldEntry>")
     *
     * @var array<\DMT\Laposta\Api\Entity\FieldEntry>
     */
    public array $data;
}
