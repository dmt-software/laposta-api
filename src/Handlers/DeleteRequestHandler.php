<?php

namespace DMT\Laposta\Api\Handlers;

use DMT\Http\Client\RequestHandlerInterface;
use DMT\Laposta\Api\Interfaces\DeleteRequest;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\RequestFactoryInterface;

class DeleteRequestHandler
{
    private RequestHandlerInterface $handler;
    private RequestFactoryInterface $factory;

    public function __construct(
        RequestHandlerInterface $handler,
        RequestFactoryInterface $factory
    ) {
        $this->handler = $handler;
        $this->factory = $factory;
    }

    /**
     * Handle a get request.
     *
     * @return void|object
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function handle(DeleteRequest $deleteRequest): void
    {
        $request = $this->factory->createRequest('delete', $deleteRequest->getUri());
        $request = $request->withUri(
            $request->getUri()->withQuery($deleteRequest->getQueryString())
        );

        $this->handler->handle($request);
    }
}
