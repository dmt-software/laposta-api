<?php

namespace DMT\Laposta\Api\Services\Processors;

use Closure;
use DMT\Laposta\Api\Entity\Event;
use DMT\Laposta\Api\Interfaces\EventProcessor;

class CallbackEventProcessor implements EventProcessor
{
    private Closure $callback;

    public function __construct(callable $callback)
    {
        if (!$callback instanceof Closure) {
            $callback = Closure::fromCallable($callback);
        }

        $this->callback = $callback;
    }

    public function process(Event $event): void
    {
        call_user_func($this->callback, $event);
    }
}
