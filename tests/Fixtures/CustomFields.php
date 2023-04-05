<?php

namespace DMT\Test\Laposta\Api\Fixtures;

use DateTime;
use DMT\Laposta\Api\Entity\CustomFields as BaseCustomFields;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * GeneratedClass: CustomFields.
 *
 * This contains the custom fields of the member entity for list `BaImMu3JZA`.
 *
 * @version Tue, 04 Apr 2023 12:58:08 GMT
 */
class CustomFields extends BaseCustomFields
{
    /**
     * @JMS\Groups({"Request"})
     * @JMS\Type("string")
     *
     * @Assert\NotBlank()
     */
    public ?string $name = null;

    /**
     * @JMS\Groups({"Request"})
     * @JMS\Type("DateTime<'Y-m-d', '', 'Y-m-d H:i:s'>")
     */
    public ?DateTime $dateofbirth = null;

    /**
     * @JMS\Groups({"Request"})
     * @JMS\Type("int")
     */
    public ?int $children = null;

    /**
     * @JMS\Groups({"Request"})
     * @JMS\Type("array")
     */
    public ?array $prefs = null;
}
