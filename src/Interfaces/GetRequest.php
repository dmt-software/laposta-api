<?php

namespace DMT\Laposta\Api\Interfaces;

interface GetRequest extends DeserializableResponse
{
    public function getUri(): string;
    public function getQueryString(): string;
}
