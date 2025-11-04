<?php

namespace DMT\Laposta\Api\Entity;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class Field
{
    /** The ID of the field in question */
    #[JMS\Type('string')]
    #[JMS\SerializedName('field_id')]
    public null|string $id = null;

    /** The ID of the list to which the field belongs */
    #[Assert\NotBlank]
    #[JMS\Type('string')]
    public string $listId;

    /** Date and time of creation */
    #[JMS\Groups(['System'])]
    #[JMS\Type("DateTime<'Y-m-d H:i:s'>")]
    public null|DateTime $created = null;

    /** Date and time of last modification made */
    #[JMS\Groups(['System'])]
    #[JMS\Type("DateTime<'Y-m-d H:i:s'>")]
    public null|DateTime $modified = null;

    /** The status of the field in question: either active or deleted */
    #[JMS\Groups(['System'])]
    #[JMS\Type('string')]
    public string $state = 'active';

    /** Name of the field (for displaying purposes) */
    #[Assert\NotBlank]
    #[JMS\Type('string')]
    public string $name;

    /** The subscriber variable for usage in campaigns (not modifiable) */
    #[JMS\Groups(['System'])]
    #[JMS\Type('string')]
    public null|string $tag = null;

    /** Name of the field (for use in member API calls) */
    #[JMS\Groups(['System'])]
    #[JMS\Type('string')]
    public null|string $customName = null;

    /** The default value (will be used in the absence of this field) */
    #[JMS\Type('string')]
    public null|string $defaultvalue = null;

    /** The data type of the field in question (text, numeric, date, select_single, select_multiple) */
    #[Assert\NotBlank]
    #[Assert\Choice(['text', 'numeric', 'date', 'select_single', 'select_multiple'])]
    #[JMS\Type('string')]
    public string $datatype = 'text';

    /** Only applicable for select_single: the desired display (select, radio) */
    #[JMS\Type('string')]
    public null|string $datatypeDisplay = null;

    /** The field position in the list */
    #[JMS\Groups(['System'])]
    #[JMS\Type('int')]
    public null|int $pos = null;

    /** An array of the available options (only for select_single or select_multiple) */
    #[JMS\Type('array')]
    public null|array $options = null;

    /**
     * An array of the available options, including IDs (only for select_single or select_multiple)
     *
     * @JMS\Type("array<DMT\Laposta\Api\Entity\Option>")
     *
     * @var array<Option>
     */
    #[JMS\Type('array<DMT\Laposta\Api\Entity\Option>')]
    public null|array $optionsFull = null;

    /** Is this a mandatory field? (true or false) */
    #[JMS\Type('bool')]
    public bool $required = false;

    /** Does this field occur in the subscription form? (true or false) */
    #[JMS\Type('bool')]
    public bool $inForm = false;

    /** Does this field occur while browsing the list? (true or false) */
    #[JMS\Type('bool')]
    public bool $inList = false;

    /** Indicator the field is the email field */
    #[JMS\Groups(['System'])]
    #[JMS\Type('bool')]
    public bool $isEmail = false;
}
