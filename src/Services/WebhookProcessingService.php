<?php

namespace DMT\Laposta\Api\Services;

use DMT\Laposta\Api\Entity\Event;
use DMT\Laposta\Api\Entity\EventCollection;
use DMT\Laposta\Api\Interfaces\EventProcessor;
use JMS\Serializer\Serializer;
use RuntimeException;

class WebhookProcessingService
{
    private Serializer $serializer;
    private EventProcessor $deactivateProcessor;
    private ?EventProcessor $subscribeProcessor;
    private ?EventProcessor $modifyProcessor;

    public function __construct(
        Serializer $serializer,
        EventProcessor $deactivateProcessor,
        EventProcessor $subscribeProcessor = null,
        EventProcessor $modifyProcessor = null
    ) {
        $this->serializer = $serializer;
        $this->deactivateProcessor = $deactivateProcessor;
        $this->subscribeProcessor = $subscribeProcessor;
        $this->modifyProcessor = $modifyProcessor;
    }

    public function process(string $webhookJson): void
    {
        $events = $this->serializer->deserialize($webhookJson, EventCollection::class, 'json');

        /** @var Event $event */
        foreach ($events as $event) {
            switch ($event->event) {
                case Event::EVENT_SUBSCRIBED:
                    $this->processEvent($this->subscribeProcessor, $event);
                    break;
                case Event::EVENT_MODIFIED:
                    $this->processEvent($this->modifyProcessor, $event);
                    break;
                case Event::EVENT_DEACTIVATED:
                    $this->processEvent($this->deactivateProcessor, $event);
            }
        }
    }

    private function processEvent(?EventProcessor $processor, Event $event): void
    {
        if ($processor === null) {
            throw new RuntimeException(
                sprintf('No processor configured for `%s` webhook', $event->event)
            );
        }

        $processor->process($event);
    }
}