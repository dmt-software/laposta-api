<?php

namespace DMT\Laposta\Api\Serializer;

use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Entity\Option;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

class FieldOptionsEventSubscriber implements \JMS\Serializer\EventDispatcher\EventSubscriberInterface
{
    public static function getSubscribedEvents(): iterable
    {
        yield [
            'event' => 'serializer.pre_serialize',
            'method' => 'handleNewOptions',
            'class' => Field::class,
            'format' => 'http-post',
            'priority' => 150,
        ];
    }

    public function handleNewOptions(PreSerializeEvent $event): void
    {
        /** @var Field $field */
        $field = $event->getObject();

        if (!$field->optionsFull) {
            return;
        }

        $options = array_filter($field->optionsFull, fn (Option $option) => $option->id == 0);

        $i = 0;
        foreach ($options as $option) {
            $option->id = $i--;
        }
    }
}
