<?php

namespace DMT\Laposta\Api\Services\Parsers;

use DMT\Laposta\Api\Interfaces\TemplateParser;

class NativePhpParser implements TemplateParser
{
    private string $template = __DIR__ . '/../../../templates/members.annotations.php';

    public function __construct(string $template = null)
    {
        $this->template = $template ?? $this->template;
    }

    public function parse(array $variables): string
    {
        try {
            ob_start();
            include $this->template;

            return ob_get_contents();
        } finally {
            ob_end_clean();
        }
    }
}
