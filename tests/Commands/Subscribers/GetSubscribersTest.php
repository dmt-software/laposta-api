<?php

namespace DMT\Test\Laposta\Api\Commands\Subscribers;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\Subscribers\GetSubscribers;
use PHPUnit\Framework\TestCase;

class GetSubscribersTest extends TestCase
{
    public function testValidCommand(): void
    {
        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new GetSubscribers('BaImMu3JZA', null), fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new GetSubscribers('', null), fn() => true);
    }
}
