<?php

namespace DMT\Test\Laposta\Api\Commands\Fields;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\Fields\DeleteField;
use PHPUnit\Framework\TestCase;

class DeleteFieldTest extends TestCase
{
    public function testValidCommand(): void
    {
        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new DeleteField('BaImMu3JZA', 'Z87ysHha9A'), fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new DeleteField('', ''), fn() => true);
    }
}
