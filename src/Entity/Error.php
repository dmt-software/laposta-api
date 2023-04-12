<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Error
 *
 * 201    The parameter is purge
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
     *
     * @JMS\Type("string")
     */
    public string $type;

    /**
     * The human-readable message.
     *
     * @JMS\Type("string")
     */
    public string $message;

    /**
     * Optional error code (see listing above)
     *
     * @JMS\Type("int")
     */
    public ?int $code = null;

    /**
     * The parameter it concerns. (optional)
     *
     * @JMS\Type("string")
     */
    public ?string $parameter = null;

    /**
     * The concerned subscriber's email or member_id (only in case of bulk insert/update)
     *
     * @JMS\Type("string")
     */
    public ?string $identification = null;
}
