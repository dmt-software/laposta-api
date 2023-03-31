<?php

namespace DMT\Laposta\Api\Handlers;

use DMT\Http\Client\RequestHandlerInterface;
use DMT\Laposta\Api\Interfaces\GetRequest;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\RequestFactoryInterface;

class GetRequestHandler
{
    private RequestHandlerInterface $handler;
    private RequestFactoryInterface $factory;
    private SerializerInterface $serializer;

    /**
     * Handle a get request.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function handle(GetRequest $getRequest)
    {
        $request = $this->factory->createRequest('get', $getRequest->getUri());
        $request = $request->withUri(
            $request->getUri()->withQuery($getRequest->getQueryString())
        );

        $response = $this->handler->handle($request);

        return $this->serializer->deserialize(
            $response->getBody()->getContents(),
            $getRequest->toEntity(),
            'json'
        );
    }
}