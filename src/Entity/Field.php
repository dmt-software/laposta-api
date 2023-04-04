<?php

namespace DMT\Laposta\Api\Entity;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class Field
{
    /**
     * The ID of the field in question
     *
     * @JMS\Groups({"Request"})
     * @JMS\Type("string")
     * @JMS\SerializedName("field_id")
     */
    public ?string $id = null;

    /**
     * The ID of the list to which the field belongs
     *
     * @JMS\Groups({"Request"})
     * @JMS\Type("string")
     * @Assert\NotBlank()
     */
    public string $listId;

    /**
     * Date and time of creation
     *
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    public ?DateTime $created = null;

    /**
     * Date and time of last modification made
     *
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    public ?DateTime $modified = null;

    /**
     * The status of the field in question: either active or deleted
     *
     * @JMS\Type("string")
     */
    public string $state = 'active';

    /**
     * Name of the field (for displaying purposes)
     *
     * @JMS\Groups({"Request"})
     * @JMS\Type("string")
     * @Assert\NotBlank()
     */
    public string $name;

    /**
     * The subscriber variable for usage in campaigns (not modifyable)
     *
     * @JMS\Type("string")
     */
    public ?string $tag = null;

    /**
     * Name of the field (for use in member API calls)
     *
     * @JMS\Type("string")
     */
    public ?string $customName = null;

    /**
     * The default value (will be used in the absence of this field)
     *
     * @JMS\Groups({"Request"})
     * @JMS\Type("string")
     */
    public ?string $defaultvalue = null;

    /**
     * The data type of the field in question (text, numeric, date, select_single, select_multiple)
     *
     * @JMS\Groups({"Request"})
     * @JMS\Type("string")
     * @Assert\NotBlank()
     * @Assert\Choice({"text", "numeric", "date", "select_single", "select_multiple"})
     */
    public string $datatype = 'text';

    /**
     * Only applicable for select_single: the desired display (select, radio)
     *
     * @JMS\Groups({"Request"})
     * @JMS\Type("string")
     */
    public ?string $datatypeDisplay = null;

    /**
     * The field position in the list
     *
     * @JMS\Type("int")
     */
    public ?int $pos = null;

    /**
     * An array of the available options (only for select_single or select_multiple)
     *
     * @JMS\Groups({"Request"})
     * @JMS\Type("array")
     */
    public ?array $options = null;

    /**
     * An array of the available options, including IDs (alleen bij select_single or select_multiple)
     *
     * @JMS\Groups({"Request"})
     * @JMS\Type("array<DMT\Laposta\Api\Entity\Option>")
     *
     * @var array<\DMT\Laposta\Api\Entity\Option>
     */
    public ?array $optionsFull = null;

    /**
     * Is this a mandatory field? (true or false)
     *
     * @JMS\Groups({"Request"})
     * @JMS\Type("bool")
     */
    public bool $required = false;

    /**
     * Does this field occur in the subscription form? (true or false)
     *
     * @JMS\Groups({"Request"})
     * @JMS\Type("bool")
     */
    public bool $inForm = false;

    /**
     * Does this field occur while browsing the list? (true or false)
     *
     * @JMS\Groups({"Request"})
     * @JMS\Type("bool")
     */
    public bool $inList = false;

    /**
     * Indicator the field is the email field
     *
     * @JMS\Type("bool")
     */
    public bool $isEmail = false;
}
