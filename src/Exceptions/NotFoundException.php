<?php

namespace DMT\Laposta\Api\Exceptions;

use Psr\Http\Client\ClientExceptionInterface;
use RuntimeException;

class NotFoundException extends RuntimeException implements ClientExceptionInterface
{
    public static function create(string $message, ?int $code = 0): self
    {
        return new self($message, (int)$code);
    }
}
