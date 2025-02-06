<?php

namespace DMT\Laposta\Api\Http;

use DMT\Http\Client\MiddlewareInterface;
use DMT\Http\Client\RequestHandlerInterface;
use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Entity\Error;
use DMT\Laposta\Api\Exceptions\ClientException;
use DMT\Laposta\Api\Exceptions\NotFoundException;
use DMT\Laposta\Api\Factories\SerializerFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ExceptionMiddleware implements MiddlewareInterface
{
    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($response->getStatusCode() < 400) {
            return $response;
        }

        $error = null;
        $body = $response->getBody()->getContents();
        if (preg_match('~^{\s*"error"~ms', $body)) {
            /** @var Error $error */
            $error = SerializerFactory::create(new Config())->deserialize($body, Error::class, 'json');

            /*
             * Work a round for member requests with email resulting in Invalid member_id,
             * when Unknown member is expected
             */
            $m = [];
            if (preg_match('~member/(\S+@\S+\.\S+)~', $request->getUri()->getPath(), $m)) {
                if (filter_var($m[1], FILTER_VALIDATE_EMAIL)) {
                    $error->code = 203;
                    $error->message = 'Unknown member';
                }
            }

            if ($error->message === 'Unknown member') {
                throw NotFoundException::create($error->message, 404);
            }
        }

        if ($response->getStatusCode() == 404) {
            throw NotFoundException::create($response->getReasonPhrase(), 404);
        }

        throw ClientException::create($response->getReasonPhrase(), $response->getStatusCode(), $request, $error);
    }
}
