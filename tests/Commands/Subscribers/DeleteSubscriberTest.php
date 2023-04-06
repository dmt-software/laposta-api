<?php

namespace DMT\Test\Laposta\Api\Commands\Subscribers;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\Subscribers\DeleteSubscriber;
use PHPUnit\Framework\TestCase;

class DeleteSubscriberTest extends TestCase
{
    public function testValidCommand(): void
    {
        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new DeleteSubscriber('BaImMu3JZA', '9978ydioiZ'), fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new DeleteSubscriber('', ''), fn() => true);
    }
}
