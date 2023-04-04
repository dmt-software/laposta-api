<?php

namespace DMT\Laposta\Api\Serializer;

use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Interfaces\Collection;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

class NormalizeNestedDataEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): iterable
    {
        yield [
            'event' => 'serializer.pre_deserialize',
            'method' => 'normalizeJsonDataArray',
            'format' => 'json',
            'priority' => 100,
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
}
