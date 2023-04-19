<?php

use DMT\Laposta\Api\Config;

$container = include(__DIR__ . '/container.php');

return $container->get(Config::class);