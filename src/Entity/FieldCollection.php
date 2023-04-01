<?php

namespace DMT\Laposta\Api\Entity;

use DMT\Laposta\Api\Interfaces\Collection;
use JMS\Serializer\Annotation as JMS;

class FieldCollection implements Collection
{
    /**
     * @JMS\Type("array<DMT\Laposta\Api\Entity\Field>")
     * @JMS\SerializedName("data")
     *
     * @var array<\DMT\Laposta\Api\Entity\Field>
     */
    public array $fields = [];
}
