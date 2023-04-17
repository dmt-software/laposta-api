<?php

namespace DMT\Laposta\Api\Interfaces;

use DMT\Laposta\Api\Entity\Event;

interface EventProcessor
{


    public function process(Event $event): void;
}