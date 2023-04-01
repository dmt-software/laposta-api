<?php

namespace DMT\Laposta\Api\Interfaces;

interface SerializableRequest
{
    public function getUri(): string;
    public function getObject(): object;
}