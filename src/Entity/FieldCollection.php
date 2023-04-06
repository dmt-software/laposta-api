<?php

namespace DMT\Laposta\Api\Entity;

use ArrayIterator;
use DMT\Laposta\Api\Interfaces\Collection;
use JMS\Serializer\Annotation as JMS;
use Traversable;

class FieldCollection implements Collection
{
    /**
     * @JMS\Type("array<DMT\Laposta\Api\Entity\Field>")
     * @JMS\SerializedName("data")
     *
     * @var array<\DMT\Laposta\Api\Entity\Field>
     */
    private array $fields = [];
    
    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->fields);
    }

    public function count()
    {
        return count($this->fields);
    }
}
