<?php

namespace DMT\Test\Laposta\Api\Commands\Fields;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\Fields\GetFields;
use PHPUnit\Framework\TestCase;

class GetFieldsTest extends TestCase
{
    public function testValidCommand(): void
    {
        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new GetFields('BaImMu3JZA'), fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new GetFields(''), fn() => true);
    }
}
