<?php

namespace DMT\Laposta\Api\Interfaces;

interface TemplateParser
{
    /**
     * The variables should at least contain the key 'class' with the binding.
     *
     * @param array $variables
     *
     * @return string
     */
    public function parse(array $variables): string;
}