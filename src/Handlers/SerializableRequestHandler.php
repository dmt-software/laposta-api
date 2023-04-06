<?php

namespace DMT\Laposta\Api\Handlers;

use DMT\Http\Client\RequestHandlerInterface;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Interfaces\SerializableRequest;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\RequestFactoryInterface;

class SerializableRequestHandler
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
     * Handle a json post request.
     *
     * @return void|object
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function handle(SerializableRequest $postRequest)
    {
        $request = $this->factory->createRequest('post', $postRequest->getUri());
        $request = $request->withAddedHeader('content-type', 'application/json');

        $payload = $this->serializer->serialize(
            $postRequest->getObject(),
            'json',
            SerializationContext::create()->setGroups('Default')
        );

        $request->getBody()->write($payload);
        $response = $this->handler->handle($request);

        if ($postRequest instanceof DeserializableResponse) {
            return $this->serializer->deserialize(
                $response->getBody()->getContents(),
                $postRequest->toEntity(),
                'json'
            );
        }
    }
}
