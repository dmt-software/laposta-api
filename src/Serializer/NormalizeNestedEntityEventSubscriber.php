<?php

namespace DMT\Laposta\Api\Serializer;

use DMT\Laposta\Api\Entity\Error;
use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Entity\MailingList;
use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Entity\Webhook;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

class NormalizeNestedEntityEventSubscriber implements EventSubscriberInterface
{
    public const ENTITIES = [
        Field::class,
        MailingList::class,
        Subscriber::class,
        Webhook::class,
    ];

    public static function getSubscribedEvents(): iterable
    {
        foreach (self::ENTITIES as $entityClass) {
            yield [
                'event' => 'serializer.pre_deserialize',
                'method' => 'normalizeEntityEntry',
                'class' => $entityClass,
                'format' => 'json',
                'priority' => 100,
            ];
        }

        yield [
            'event' => 'serializer.pre_deserialize',
            'method' => 'normalizeErrorEntry',
            'class' => Error::class,
            'format' => 'json',
            'priority' => 100,
        ];
    }

    public function normalizeEntityEntry(PreDeserializeEvent $event): void
    {
        $event->setData(array_values($event->getData())[0]);
    }

    public function normalizeErrorEntry(PreDeserializeEvent $event): void
    {
        $data = $event->getData();
        $error = $data['error'];

        if (isset($data['provided_data'])) {
            if (isset($data['provided_data']['email'])) {
                $error['identification'] = $data['provided_data']['email'];
            } else {
                $error['identification'] = $data['provided_data']['member_id'] ?? null;
            }
        }

        $event->setData($error);

    }
}
