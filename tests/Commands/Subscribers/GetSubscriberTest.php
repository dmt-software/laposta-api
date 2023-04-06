<?php

namespace DMT\Test\Laposta\Api\Commands\Subscribers;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\Subscribers\GetSubscriber;
use PHPUnit\Framework\TestCase;

class GetSubscriberTest extends TestCase
{
    public function testValidCommand(): void
    {
        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new GetSubscriber('BaImMu3JZA', '9978ydioiZ'), fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new GetSubscriber('', ''), fn() => true);
    }
}
