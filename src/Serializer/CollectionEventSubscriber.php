<?php

namespace DMT\Laposta\Api\Serializer;

use DMT\Laposta\Api\Interfaces\Collection;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

class CollectionEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        yield [
            'event' => 'serializer.pre_deserialize',
            'method' => 'normalizeDataArray',
            'format' => 'json',
            'priority' => 100,
        ];
    }

    public function normalizeDataArray(PreDeserializeEvent $event)
    {
        $entity = $event->getType()['name'];
        if (!is_a($entity, Collection::class, true)) {
            $data = [];
            foreach ($event->getData()['data'] ?? [] as $array) {
                $data[] = array_pop($array);
            }
            $event->setData(compact('data'));
        }
    }
}
