<?php

namespace DMT\Test\Laposta\Api\Serializer;

use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Entity\Option;
use DMT\Laposta\Api\Serializer\FieldOptionsEventSubscriber;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use PHPUnit\Framework\TestCase;

class FieldOptionsEventSubscriberTest extends TestCase
{
    public function testHandleNewOptions()
    {
        $field = new Field();
        $field->optionsFull = [];
        $field->optionsFull[0] = new Option();
        $field->optionsFull[0]->id = 13;
        $field->optionsFull[0]->value = 'old value 13';
        $field->optionsFull[1] = new Option();
        $field->optionsFull[1]->value = 'new value 15';
        $field->optionsFull[2] = new Option();
        $field->optionsFull[2]->id = 14;
        $field->optionsFull[2]->value = 'old value 14';
        $field->optionsFull[3] = new Option();
        $field->optionsFull[3]->value = 'new value 16';

        $event = $this->getMockBuilder(PreSerializeEvent::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getObject'])
            ->getMock();
        $event->expects($this->exactly(2))->method('getObject')->willReturn($field);

        $eventSubscriber = new FieldOptionsEventSubscriber();
        $eventSubscriber->handleNewOptions($event);

        $options = [];
        array_map(
            function (Option $option) use (&$options) {
                $options[$option->id] = $option->value;
            },
            $event->getObject()->optionsFull
        );

        $this->assertArrayHasKey(0, $options);
        $this->assertSame('new value 15', $options[0]);
        $this->assertArrayHasKey(-1, $options);
        $this->assertSame('new value 16', $options[-1]);
        $this->assertArrayHasKey(13, $options);
        $this->assertSame('old value 13', $options[13]);
        $this->assertArrayHasKey(14, $options);
        $this->assertSame('old value 14', $options[14]);
    }
}
