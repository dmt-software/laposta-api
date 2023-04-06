<?php

namespace DMT\Test\Laposta\Api\Commands\Fields;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\Fields\CreateField;
use DMT\Laposta\Api\Entity\Field;
use PHPUnit\Framework\TestCase;

class CreateFieldTest extends TestCase
{
    public function testValidCommand(): void
    {
        $field = new Field();
        $field->listId = 'BaImMu3JZA';
        $field->name = 'company';
        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new CreateField($field), fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new CreateField(new Field()), fn() => true);
    }
}
