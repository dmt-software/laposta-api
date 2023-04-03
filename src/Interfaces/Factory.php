<?php

namespace DMT\Laposta\Api\Interfaces;

use DMT\Laposta\Api\Config;

interface Factory
{
    public static function create(Config $config): object;
}