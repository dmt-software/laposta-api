<?php

namespace DMT\Test\Laposta\Api\Commands\Fields;

use DMT\Laposta\Api\Commands\Fields\UpdateField;
use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Entity\Field;
use PHPUnit\Framework\TestCase;

class UpdateFieldTest extends TestCase
{
    public function testValidCommand(): void
    {
        $field = new Field();
        $field->id = 'Z87ysHha9A';
        $field->listId = 'BaImMu3JZA';

        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new UpdateField($field), fn() => true));
    }

    /**
     * @dataProvider invalidFieldProvider
     */
    public function testInvalidCommand(Field $field): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new UpdateField($field), fn($command) => $command->getUri() && $command->getPayload());
    }

    public function invalidFieldProvider(): iterable
    {
        $field = new Field();
        $field->id = 'Z87ysHha9A';
        $field->listId = '';


        return [
            'update without id' => [new Field()],
            'update without list' => [$field]
        ];
    }
}
