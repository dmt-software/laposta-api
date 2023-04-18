<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

class Option
{
    /**
     * The option id
     *
     * @JMS\Type("int")
     */
    public ?int $id = null;

    /**
     * The value for the option
     *
     * @JMS\Type("string")
     */
    public string $value;
}
