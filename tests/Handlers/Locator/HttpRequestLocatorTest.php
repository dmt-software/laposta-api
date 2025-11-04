<?php

namespace DMT\Test\Laposta\Api\Handlers\Locator;

use DMT\Http\Client\RequestHandlerInterface;
use DMT\Laposta\Api\Handlers\DeleteRequestHandler;
use DMT\Laposta\Api\Handlers\GetRequestHandler;
use DMT\Laposta\Api\Handlers\Locator\HttpRequestLocator;
use DMT\Laposta\Api\Handlers\PostRequestHandler;
use DMT\Laposta\Api\Handlers\SerializableRequestHandler;
use DMT\Laposta\Api\Interfaces\DeleteRequest;
use DMT\Laposta\Api\Interfaces\GetRequest;
use DMT\Laposta\Api\Interfaces\PostRequest;
use DMT\Laposta\Api\Interfaces\SerializableRequest;
use JMS\Serializer\SerializerInterface;
use League\Tactician\Exception\MissingHandlerException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use stdClass;

class HttpRequestLocatorTest extends TestCase
{
    #[DataProvider('handlerForCommandProvider')]
    public function testGetHandlerForCommand(string $command, string $expectedHandler): void
    {
        $locator = new HttpRequestLocator(
            $this->getMockBuilder(RequestHandlerInterface::class)->getMock(),
            $this->getMockBuilder(RequestFactoryInterface::class)->getMock(),
            $this->getMockBuilder(SerializerInterface::class)->getMock()
        );

        $this->assertInstanceOf($expectedHandler, $locator->getHandlerForCommand($command));
    }

    public static function handlerForCommandProvider(): iterable
    {
        return [
            [DeleteRequest::class, DeleteRequestHandler::class],
            [GetRequest::class, GetRequestHandler::class],
            [PostRequest::class, PostRequestHandler::class],
            [SerializableRequest::class, SerializableRequestHandler::class],
        ];
    }

    public function testIllegalCommand(): void
    {
        $this->expectException(MissingHandlerException::class);

        $locator = new HttpRequestLocator(
            $this->getMockBuilder(RequestHandlerInterface::class)->getMock(),
            $this->getMockBuilder(RequestFactoryInterface::class)->getMock(),
            $this->getMockBuilder(SerializerInterface::class)->getMock()
        );

        $locator->getHandlerForCommand(stdClass::class);
    }
}
