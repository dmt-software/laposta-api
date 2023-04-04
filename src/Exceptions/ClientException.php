<?php

namespace DMT\Laposta\Api\Exceptions;

use DMT\Laposta\Api\Entity\Error;
use DMT\Laposta\Api\Interfaces\Exception;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use RuntimeException;

class ClientException extends RuntimeException implements Exception, RequestExceptionInterface
{
    private ?Error $error;
    private RequestInterface $request;

    public static function create(string $message, int $code, RequestInterface $request, Error $error = null): self
    {
        $exception = new self($message, $code);
        $exception->request = $request;
        $exception->error = $error;

        return $exception;
    }

    public function getError(): ?Error
    {
        return $this->error;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
