<?php

namespace DMT\Test\Laposta\Api\Entity;

use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Entity\Option;
use DMT\Laposta\Api\Factories\SerializerFactory;
use JMS\Serializer\SerializationContext;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    public function testSerializeForPost(): void
    {
        $option = function($id, $value) {
            $option = new Option();
            $option->id = $id;
            $option->value = $value;

            return $option;
        };
        
        $field = new Field();
        $field->listId = 'BaImMu3JZA';
        $field->name = 'Experience';
        $field->datatype = 'select_single';
        $field->optionsFull = [
            $option(1, 'less than 1 year'),
            $option(2, 'between 1 and 5 years'),
            $option(5, 'between 5 and 10 years'),
            $option(10, 'more than 10 years')
        ];

        $payload = SerializerFactory::create(new Config())->serialize(
            $field,
            'http-post',
            SerializationContext::create()->setGroups('Default')
        );

        $this->assertStringContainsString('list_id=BaImMu3JZA', $payload);
        $this->assertStringContainsString('name=Experience', $payload);
        $this->assertStringContainsString('datatype=select_single', $payload);
        $this->assertStringContainsString('options_full%5B1%5D=less+than+1+year', $payload);
        $this->assertStringContainsString('options_full%5B2%5D=between+1+and+5+years', $payload);
        $this->assertStringContainsString('options_full%5B5%5D=between+5+and+10+years', $payload);
        $this->assertStringContainsString('options_full%5B10%5D=more+than+10+years', $payload);
    }
}
