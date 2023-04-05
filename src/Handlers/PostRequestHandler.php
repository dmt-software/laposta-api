<?php

namespace DMT\Laposta\Api\Handlers;

use DMT\Http\Client\RequestHandlerInterface;
use DMT\Laposta\Api\Commands\Subscribers\CreateSubscriber;
use DMT\Laposta\Api\Interfaces\PostRequest;
use DMT\Laposta\Api\Interfaces\DeserializableResponse;
use DMT\Laposta\Api\Serializer\ExistingObjectConstructor;
use DMT\Laposta\Api\Serializer\SubscribeOptionsEventSubscriber;
use JMS\Serializer\DeserializationContext;
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
     * Handle a post request.
     *
     * @return void|object
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function handle(PostRequest $postRequest): void
    {
        $request = $this->factory->createRequest('post', $postRequest->getUri());
        $request = $request->withAddedHeader('content-type', 'application/x-www-form-urlencoded');

        $context = SerializationContext::create();
        $context->setGroups('Request');

        if ($postRequest instanceof CreateSubscriber) {
            $context->setAttribute(
                SubscribeOptionsEventSubscriber::ATTRIBUTE,
                $postRequest->getSubscribeOptions()
            );
        }

        $payload = $this->serializer->serialize($postRequest->getPayload(), 'http-post', $context);

        $request->getBody()->write($payload);
        $response = $this->handler->handle($request);

        if ($postRequest instanceof DeserializableResponse) {
            $context = DeserializationContext::create()->setAttribute(
                ExistingObjectConstructor::ATTRIBUTE,
                $postRequest->getPayload()
            );

            $this->serializer->deserialize(
                $response->getBody()->getContents(),
                $postRequest->toEntity(),
                'json',
                $context
            );
        }
    }
}
