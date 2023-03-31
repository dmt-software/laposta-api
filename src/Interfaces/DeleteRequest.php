<?php

namespace DMT\Laposta\Api\Interfaces;

interface DeleteRequest
{
    public function getUri(): string;
    public function getQueryString(): ?string;
}
