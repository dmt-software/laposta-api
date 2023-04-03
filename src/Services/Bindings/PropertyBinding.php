<?php

namespace DMT\Laposta\Api\Services\Bindings;

class PropertyBinding
{
    /**
     * Name of the property.
     */
    public string $name;

    /**
     * The data type for the property.
     */
    public string $type = 'string';

    /**
     * Indication if the property is mandatory or not.
     */
    public bool $required = false;

    /**
     * The default value.
     */
    public ?string $default = null;
}
