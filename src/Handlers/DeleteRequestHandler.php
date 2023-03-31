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

    /**
     * Handle a get request.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function handle(DeleteRequest $getRequest)
    {
        $request = $this->factory->createRequest('delete', $getRequest->getUri());
        $request = $request->withUri(
            $request->getUri()->withQuery($getRequest->getQueryString())
        );

        $response = $this->handler->handle($request);

        if ($getRequest instanceof DeserializableResponse) {
            return $this->serializer->deserialize(
                $response->getBody()->getContents(),
                $getRequest->toEntity(),
                'json'
            );
        }
    }
}