<?php

namespace DMT\Laposta\Api\Entity;

/**
 * Class Error
 *
 * 201    The parameter is empty
 * 202    The syntax of the parameter is incorrect
 * 203    The parameter is unknown
 * 204    The parameter already exists
 * 205    The parameter is not a number
 * 206    The parameter is not a boolean (true/false)
 * 207    The parameter is not a date
 * 208    The parameter is not an email address
 * 209    The parameter is not a URL
 * 999    The parameter contains another error
 */
class Error
{
    /**
     * The type of error.
     */
    public string $type;

    /**
     * The human-readable message.
     */
    public string $message;

    /**
     * Optional error code (see listing above)
     */
    public ?int $code = null;

    /**
     * The parameter it concerns. (optional)
     */
    public ?string $parameter = null;
}
