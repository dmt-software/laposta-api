<?php

namespace DMT\Laposta\Api\Serializer;

use DMT\Laposta\Api\Entity\Subscriber;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;

class SubscribeOptionsEventSubscriber implements EventSubscriberInterface
{
    public const ATTRIBUTE = 'subscribe-options';

    public static function getSubscribedEvents(): iterable
    {
        yield [
            'event' => 'serializer.post_serialize',
            'method' => 'appendSubscribeOptions',
            'class' => Subscriber::class,
            'format' => 'http-post',
            'priority' => -1000,
        ];
    }

    public function appendSubscribeOptions(ObjectEvent $event): void
    {
        if (!$event->getContext()->hasAttribute(self::ATTRIBUTE)) {
            return;
        }

        foreach ($event->getContext()->getAttribute(self::ATTRIBUTE) as $key => $value) {
            $event->getVisitor()->visitProperty(
                new StaticPropertyMetadata(Subscriber::class, $key, $value),
                $value
            );
        }
    }
}
