<?php

namespace DMT\Laposta\Api\Services\Bindings;

class ClassBinding
{
    /**
     * The class name.
     */
    public ?string $name;

    /**
     * The namespace of the class.
     */
    public ?string $namespace;

    /**
     * @var array<\DMT\Laposta\Api\Services\Bindings\PropertyBinding>
     */
    public array $properties = [];

    public function __construct(string $name = null)
    {
        $this->name = $name;

        if (preg_match('~^(.*)\\\\([^\\\\]+)$~', $name, $match)) {
            [$this->namespace, $this->name] = [$match[1], $match[2]];
        }
    }
}
