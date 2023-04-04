<?php

namespace DMT\Laposta\Api\Serializer;

use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Entity\CustomFields;
use DMT\Laposta\Api\Entity\Subscriber;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

class CustomFieldsDiscriminatorEventSubscriber implements EventSubscriberInterface
{
    private Config $config;
    private string $listId;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public static function getSubscribedEvents(): iterable
    {
        yield [
            'event' => 'serializer.pre_deserialize',
            'method' => 'setListId',
            'class' => Subscriber::class,
            'format' => 'json',
        ];

        yield [
            'event' => 'serializer.pre_deserialize',
            'method' => 'discriminate',
            'class' => CustomFields::class,
            'format' => 'json',
        ];
    }

    public function setListId(PreDeserializeEvent $event)
    {
        $data = $event->getData();
        $data['custom_fields']['list_id'] = $data['list_id'];
        $event->setData($data);
    }

    public function discriminate(PreDeserializeEvent $event)
    {
        $listId = $event->getData()['list_id'] ?? '';

        $event->setType($this->config->customFieldsClasses[$listId] ?? \stdClass::class);
    }
}
