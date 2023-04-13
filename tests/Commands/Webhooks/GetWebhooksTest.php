<?php

namespace DMT\Test\Laposta\Api\Commands\Webhooks;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\Webhooks\GetWebhooks;
use PHPUnit\Framework\TestCase;

class GetWebhooksTest extends TestCase
{
    public function testValidCommand(): void
    {
        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new GetWebhooks('BaImMu3JZA'), fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new GetWebhooks(''), fn() => true);
    }
}
