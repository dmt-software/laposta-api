<?php

namespace DMT\Test\Laposta\Api\Fixtures;

use DateTime;
use DMT\Laposta\Api\Entity\BaseCustomFields;
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
    #[Assert\NotBlank]
    #[JMS\Type('string')]
    public null|string $name = null;

    #[JMS\Type("DateTime<'Y-m-d', '', 'Y-m-d H:i:s'>")]
    public null|DateTime $dateofbirth = null;

    #[JMS\Type('int')]
    public null|int $children = null;

    #[JMS\Type('array')]
    public null|array $prefs = null;
}
