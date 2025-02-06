<?php

namespace DMT\Test\Laposta\Api\Http;

use DMT\Http\Client\RequestHandlerInterface;
use DMT\Laposta\Api\Entity\Error;
use DMT\Laposta\Api\Exceptions\ClientException;
use DMT\Laposta\Api\Exceptions\NotFoundException;
use DMT\Laposta\Api\Http\ExceptionMiddleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ExceptionMiddlewareTest extends TestCase
{
    public function testThrowNotFoundException(): void
    {
        $this->expectException(NotFoundException::class);

        $handler = $this->getMockForAbstractClass(RequestHandlerInterface::class);
        $handler->expects($this->once())->method('handle')->willReturn((new Response())->withStatus(404, 'Not found'));

        $middleware = new ExceptionMiddleware();
        $middleware->process(new Request('GET', 'http://localhost/'), $handler);
    }

    /**
     * @dataProvider errorResponseProvider
     */
    public function testThrowException(ResponseInterface $response, array $error): void
    {
        $handler = $this->getMockForAbstractClass(RequestHandlerInterface::class);
        $handler->expects($this->once())->method('handle')->willReturn($response);

        if ($error) {
            $response->getBody()->write(json_encode(compact('error')));
            $response->getBody()->rewind();
        }

        try {
            $middleware = new ExceptionMiddleware();
            $middleware->process(new Request('GET', 'http://localhost/'), $handler);

            $this->expectException(ClientException::class);
        } catch (ClientException $exception) {
            $this->assertInstanceOf(RequestInterface::class, $exception->getRequest());
            $this->assertInstanceOf(Error::class, $exception->getError());

            $this->assertSame($error['type'], $exception->getError()->type);
            $this->assertSame($error['message'], $exception->getError()->message);
            $this->assertSame($error['code'] ?? null, $exception->getError()->code);
            $this->assertSame($error['parameter'] ?? null, $exception->getError()->parameter);
        }
    }

    public function testEmptyResponseBody(): void
    {
        $handler = $this->getMockForAbstractClass(RequestHandlerInterface::class);
        $handler->expects($this->once())->method('handle')->willReturn(new Response(500));

        try {
            $middleware = new ExceptionMiddleware();
            $middleware->process(new Request('GET', 'http://localhost/'), $handler);

            $this->expectException(ClientException::class);
        } catch (ClientException $exception) {
            $this->assertNull($exception->getError());
        }
    }

    public function errorResponseProvider(): iterable
    {
        $response = new Response();

        return [
            'incorrect email' => [
                $response->withStatus(400, 'Bad Request'),
                [
                    'type' => 'invalid_input',
                    'message' => 'Email: invalid address',
                    'code' => 208,
                    'parameter' => 'email'
                ]
            ],
            'unauthorized request' => [
                $response->withStatus(401, 'Unauthorized'),
                [
                    'type' => 'invalid_request',
                    'message' => 'No valid API key was provided',
                ]
            ],
            'server error' => [
                $response->withStatus(500, 'Server Error'),
                [
                    'type' => 'internal',
                    'message' => 'temporary unavailable',
                ]
            ]
        ];
    }

    /**
     * @dataProvider memberNotFoundErrorProvider
     */
    public function testNotFoundExceptionWorkARound(string $identifier, array $error): void
    {
        $handler = $this->getMockForAbstractClass(RequestHandlerInterface::class);
        $handler->expects($this->once())->method('handle')->willReturn($response = new Response(400));

        $response->getBody()->write(json_encode(compact('error')));
        $response->getBody()->rewind();

        try {
            $middleware = new ExceptionMiddleware();
            $middleware->process(new Request('GET', 'http://localhost/member/' . $identifier), $handler);

            $this->expectException(NotFoundException::class);
        } catch (NotFoundException $exception) {
            $this->assertSame('Unknown member', $exception->getMessage());
            $this->assertSame(404, $exception->getCode());
        }
    }

    public function memberNotFoundErrorProvider(): iterable
    {
        return [
            'using email' => [
                'jhon.do@example.org',
                [
                    'type' => 'invalid_input',
                    'message' => 'Invalid member_id',
                    'code' => 202,
                    'parameter' => 'member_id',
                ]
            ],
            'using member_id' => [
                'azmsylzsf9',
                [
                    'type' => 'invalid_input',
                    'message' => 'Unknown member',
                    'code' => 203,
                    'parameter' => 'member_id',
                ]
            ]
        ];
    }
}
