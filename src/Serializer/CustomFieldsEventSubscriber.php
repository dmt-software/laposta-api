<?php

namespace DMT\Laposta\Api\Serializer;

use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Entity\CustomField;
use DMT\Laposta\Api\Entity\BaseCustomFields;
use DMT\Laposta\Api\Entity\CustomFields;
use DMT\Laposta\Api\Entity\Subscriber;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use JMS\Serializer\Metadata\PropertyMetadata;

class CustomFieldsEventSubscriber implements EventSubscriberInterface
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public static function getSubscribedEvents(): iterable
    {
        yield [
            'event' => 'serializer.pre_deserialize',
            'method' => 'prepare',
            'class' => Subscriber::class,
            'format' => 'json',
            'priority' => 50,
        ];

        yield [
            'event' => 'serializer.pre_deserialize',
            'method' => 'discriminate',
            'class' => BaseCustomFields::class,
            'format' => 'json',
            'priority' => 25,
        ];

        yield [
            'event' => 'serializer.pre_deserialize',
            'method' => 'mixedType',
            'class' => CustomField::class,
            'format' => 'json',
        ];
    }

    /**
     * Prepare CustomFields.
     *
     * Stores the custom fields in the right format.
     */
    public function prepare(PreDeserializeEvent $event)
    {
        $data = $event->getData();
        $listId = $data['list_id'] ?? '';

        if (array_key_exists($listId, $this->config->customFieldsClasses)) {
            $data['custom_fields']['list_id'] = $data['list_id'];
        } elseif (array_key_exists('custom_fields', $data)) {
            $result = [];
            foreach ($data['custom_fields'] as $name => $value) {
                $result[] = compact('name', 'value');
            }
            $data['custom_fields'] = $result;
        }

        $event->setData($data);
    }

    /**
     * Discriminator that chooses the right CustomFields implementation.
     */
    public function discriminate(PreDeserializeEvent $event): void
    {
        $data = $event->getData();
        $listId = $data['list_id'] ?? '';

        $event->setType($this->config->customFieldsClasses[$listId] ?? CustomFields::class);

        if (!array_key_exists($listId, $this->config->customFieldsClasses)) {
            return;
        }

        $metadata = $event->getContext()
            ->getMetadataFactory()
            ->getMetadataForClass($this->config->customFieldsClasses[$listId]);

        /** @var PropertyMetadata $property */
        foreach ($metadata->propertyMetadata as $property) {
            if ($property->type['name'] === 'DateTime' && $data[$property->serializedName] === '') {
                $data[$property->serializedName] = null;
            }
        }

        $event->setData($data);
    }

    /**
     * Toggle the right type for the CustomField::value.
     */
    public function mixedType(PreDeserializeEvent $event): void
    {
        $metadata = $event->getContext()->getMetadataFactory()->getMetadataForClass(CustomField::class);

        /** @var array<string, PropertyMetadata> $propertyMetadata */
        $propertyMetadata = $metadata->propertyMetadata;

        if (is_array($event->getData()['value'] ?? null)) {
            $propertyMetadata['value']->type = ['name' => 'array', 'params' => []];
        } else {
            $propertyMetadata['value']->type = ['name' => 'string', 'params' => []];
        }
    }
}
