<?php

use Psr\Container\ContainerInterface;

return new class() implements ContainerInterface
{
    public function get(string $id)
    {
        return $id::load(__DIR__ . '/config.php');
    }

    public function has(string $id)
    {
        return true;
    }
};
