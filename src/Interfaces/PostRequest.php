<?php

namespace DMT\Laposta\Api\Interfaces;

interface PostRequest
{
    public function getUri(): string;
    public function getPayload(): object;
}
