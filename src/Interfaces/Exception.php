<?php

namespace DMT\Laposta\Api\Interfaces;

use DMT\Laposta\Api\Entity\Error;
use Throwable;

interface Exception extends Throwable
{
    public function getError(): ?Error;
}
