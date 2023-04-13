<?php

namespace DMT\Test\Laposta\Api\Commands\Webhooks;

use DMT\CommandBus\Validator\ValidationException;
use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\Laposta\Api\Commands\Webhooks\GetWebhook;
use PHPUnit\Framework\TestCase;

class GetWebhookTest extends TestCase
{
    public function testValidCommand(): void
    {
        $validator = new ValidationMiddleware();

        $this->assertTrue($validator->execute(new GetWebhook('BaImMu3JZA', 'cW5ls8IVJl'), fn() => true));
    }

    public function testInvalidCommand(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidationMiddleware();
        $validator->execute(new GetWebhook('', ''), fn() => true);
    }
}
