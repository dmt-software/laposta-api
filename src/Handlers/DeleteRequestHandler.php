<?php

namespace DMT\Laposta\Api\Handlers;

use DMT\Http\Client\RequestHandlerInterface;
use DMT\Laposta\Api\Interfaces\DeleteRequest;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\RequestFactoryInterface;

class DeleteRequestHandler
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
     * Handle a get request.
     *
     * @return void|object
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function handle(DeleteRequest $deleteRequest)
    {
        $request = $this->factory->createRequest('delete', $deleteRequest->getUri());
        $request = $request->withUri(
            $request->getUri()->withQuery($deleteRequest->getQueryString())
        );

        $response = $this->handler->handle($request);

        if ($deleteRequest instanceof DeserializableResponse) {
            return $this->serializer->deserialize(
                $response->getBody()->getContents(),
                $deleteRequest->toEntity(),
                'json'
            );
        }
    }
}