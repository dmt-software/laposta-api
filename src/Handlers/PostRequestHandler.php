<?php

namespace DMT\Laposta\Api\Handlers;

use DMT\Http\Client\RequestHandlerInterface;
use DMT\Laposta\Api\Interfaces\PostRequest;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * This handles request with post data.
 */
class PostRequestHandler
{
    private RequestHandlerInterface $handler;
    private RequestFactoryInterface $factory;
    private SerializerInterface $serializer;

    /**
     * Handle a post request.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function handle(PostRequest $postRequest)
    {
        $request = $this->factory->createRequest('post', $postRequest->getUri());
        $request = $request->withAddedHeader('content-type', 'application/x-www-form-urlencoded');

        $payload = $this->serializer->serialize(
            $postRequest->getPayload(),
            'post-data',
            SerializationContext::create()->setGroups('Request')
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
