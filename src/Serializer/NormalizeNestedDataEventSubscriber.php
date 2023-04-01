<?php

namespace DMT\Laposta\Api\Serializer;

use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Interfaces\Collection;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

class NormalizeNestedDataEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        yield [
            'event' => 'serializer.pre_deserialize',
            'method' => 'normalizeJsonDataArray',
            'format' => 'json',
            'priority' => 100,
        ];

        yield [
            'event' => 'serializer.pre_deserialize',
            'method' => 'normalizeFieldOptions',
            'class' => Field::class,
            'format' => 'json',
        ];
    }

    public function normalizeJsonDataArray(PreDeserializeEvent $event)
    {
        $entity = $event->getType()['name'];
        if (is_a($entity, Collection::class, true)) {
            $data = [];
            foreach ($event->getData()['data'] ?? [] as $array) {
                $data[] = array_pop($array);
            }
            $event->setData(compact('data'));
        } elseif ($event->getContext()->getDepth() == 1) { // use interface here too
            $event->setData(array_values($event->getData())[0]);
        }
    }

    public function normalizeFieldOptions(PreDeserializeEvent $event)
    {
        $field = $event->getData();
        if ($field['options_full'] ?? null) {
            $options = [];
            foreach ($field['options_full'] as $option) {
                if ($option['id'] !== null) {
                    $options[$option['id']] = $option['value'];
                }
            }
            $field['options_full'] = $options;
            $event->setData($field);
        }
    }
}
