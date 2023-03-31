<?php

namespace DMT\Laposta\Api\Interfaces;

interface DeserializableResponse
{
    public function toEntity(): string;
}
