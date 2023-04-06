<?php

namespace DMT\Test\Laposta\Api\Commands\Fields;

use DMT\Laposta\Api\Commands\Fields\GetField;
use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use PHPUnit\Framework\TestCase;

class GetFieldTest extends TestCase
{
    public function testValidCommand(): void
    {
        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new GetField('BaImMu3JZA', 'Z87ysHha9A'), fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new GetField('', ''), fn() => true);
    }
}
