<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

class Option
{
    /**
     * The option id
     */
    public int $id = 0;

    /**
     * The value for the option
     */
    public string $value;
}