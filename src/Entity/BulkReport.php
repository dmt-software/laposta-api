<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

class BulkReport
{
    /**
     * @JMS\Type("DMT\Laposta\Api\Entity\BulkSummary")
     * @JMS\SerializedName("report")
     */
    public BulkSummary $summary;

    /**
     * @JMS\Type("array<DMT\Laposta\Api\Entity\Error>")
     *
     * @var array<Error>
     */
    public array $errors;
}
