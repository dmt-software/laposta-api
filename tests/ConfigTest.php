<?php

namespace DMT\Test\Laposta\Api;

use DMT\Laposta\Api\Config;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    #[DataProvider('configFileProvider')]
    public function testLoadFromArray(string $file): void
    {
        $config = Config::load($file);

        $this->assertInstanceOf(Config::class, $config);
        $this->assertSame('JdMtbsMq2jqJdQZD9AHC', $config->apiKey);
    }

    public static function configFileProvider(): iterable
    {
        return [
            'include config array' => [__DIR__ . '/Fixtures/config.php'],
            'include from callable' => [__DIR__ . '/Fixtures/callable.php'],
            'include file containing config instance' => [__DIR__ . '/Fixtures/include.php'],
            'include bootstrap from container' => [__DIR__ . '/Fixtures/bootstrap.php'],
        ];
    }
}
