<?php

namespace DMT\Laposta\Api\Handlers\Locator;

use DMT\Http\Client\RequestHandlerInterface;
use DMT\Laposta\Api\Handlers\DeleteRequestHandler;
use DMT\Laposta\Api\Handlers\GetRequestHandler;
use DMT\Laposta\Api\Handlers\PostRequestHandler;
use DMT\Laposta\Api\Handlers\SerializableRequestHandler;
use DMT\Laposta\Api\Interfaces\DeleteRequest;
use DMT\Laposta\Api\Interfaces\GetRequest;
use DMT\Laposta\Api\Interfaces\PostRequest;
use DMT\Laposta\Api\Interfaces\SerializableRequest;
use JMS\Serializer\SerializerInterface;
use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;
use Psr\Http\Message\RequestFactoryInterface;

class HttpRequestLocator implements HandlerLocator
{
    private RequestHandlerInterface $handler;
    private RequestFactoryInterface $factory;
    private SerializerInterface $serializer;

    public function __construct(
        RequestHandlerInterface $handler,
        RequestFactoryInterface $factory,
        SerializerInterface $serializer
    ) {
        $this->handler = $handler;
        $this->factory = $factory;
        $this->serializer = $serializer;
    }

    /**
     * Get Handler for the request.
     *
     * @param string $commandName
     *
     * @throws \League\Tactician\Exception\MissingHandlerException
     */
    public function getHandlerForCommand($commandName)
    {
        if (is_a($commandName, SerializableRequest::class, true)) {
            return new SerializableRequestHandler($this->handler, $this->factory, $this->serializer);
        }

        if (is_a($commandName, PostRequest::class, true)) {
            return new PostRequestHandler($this->handler, $this->factory, $this->serializer);
        }

        if (is_a($commandName, GetRequest::class, true)) {
            return new GetRequestHandler($this->handler, $this->factory, $this->serializer);
        }

        if (is_a($commandName, DeleteRequest::class, true)) {
            return new DeleteRequestHandler($this->handler, $this->factory);
        }

        throw MissingHandlerException::forCommand($commandName);
    }
}
