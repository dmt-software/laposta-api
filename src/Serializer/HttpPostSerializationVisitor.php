<?php

namespace DMT\Laposta\Api\Serializer;

use JMS\Serializer\AbstractVisitor;
use JMS\Serializer\Exception\NotAcceptableException;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class HttpPostSerializationVisitor extends AbstractVisitor implements SerializationVisitorInterface
{
    /**
     * @var int
     */
    private $encodingType;

    /**
     * @var array
     */
    private $dataStack;
    /**
     * @var \ArrayObject|array
     */
    private $data;

    public function __construct(int $encodingType = PHP_QUERY_RFC1738)
    {
        $this->dataStack = [];
        $this->encodingType = $encodingType;
    }

    /**
     * {@inheritdoc}
     */
    public function visitNull($data, array $type)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function visitString(string $data, array $type)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitBoolean(bool $data, array $type)
    {
        return $data ? 'true' : 'false';
    }

    /**
     * {@inheritdoc}
     */
    public function visitInteger(int $data, array $type)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitDouble(float $data, array $type)
    {
        $precision = $type['params'][0] ?? null;
        if (!is_int($precision)) {
            return $data;
        }

        $roundMode = $type['params'][1] ?? null;
        $roundMode = $this->mapRoundMode($roundMode);

        return round($data, $precision, $roundMode);
    }

    /**
     * @param array $data
     * @param array $type
     *
     * @return array|\ArrayObject
     */
    public function visitArray(array $data, array $type)
    {
        \array_push($this->dataStack, $data);

        $rs = isset($type['params'][1]) ? new \ArrayObject() : [];

        $isList = isset($type['params'][0]) && !isset($type['params'][1]);

        $elType = $this->getElementType($type);
        foreach ($data as $k => $v) {
            try {
                $v = $this->navigator->accept($v, $elType);
            } catch (NotAcceptableException $e) {
                continue;
            }

            if ($isList) {
                $rs[] = $v;
            } else {
                $rs[$k] = $v;
            }
        }

        \array_pop($this->dataStack);

        return $rs;
    }

    public function startVisitingObject(ClassMetadata $metadata, object $data, array $type): void
    {
        \array_push($this->dataStack, $this->data);
        $this->data = true === $metadata->isMap ? new \ArrayObject() : [];
    }

    /**
     * @return array|\ArrayObject
     */
    public function endVisitingObject(ClassMetadata $metadata, object $data, array $type)
    {
        $rs = $this->data;
        $this->data = \array_pop($this->dataStack);

        if (true !== $metadata->isList && empty($rs)) {
            return new \ArrayObject();
        }

        return $rs;
    }

    /**
     * {@inheritdoc}
     */
    public function visitProperty(PropertyMetadata $metadata, $v): void
    {
        try {
            $v = $this->navigator->accept($v, $metadata->type);
        } catch (NotAcceptableException $e) {
            return;
        }

        if (true === $metadata->skipWhenEmpty && ($v instanceof \ArrayObject || \is_array($v)) && 0 === count($v)) {
            return;
        }

        if ($metadata->inline) {
            if (\is_array($v) || ($v instanceof \ArrayObject)) {
                // concatenate the two array-like structures
                // is there anything faster?
                foreach ($v as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        } else {
            $this->data[$metadata->serializedName] = $v;
        }
    }

    public function getResult($data)
    {
        unset($this->navigator);

        foreach ($data as &$value) {
            if (is_array($value) && is_array(current($value))) {
                $keys = array_map('key', $value);
                $values = array_map('current', $value);

                $value = array_combine($keys, $values);
            }
        }

        return http_build_query($data, '', '&', $this->encodingType);
    }
}
