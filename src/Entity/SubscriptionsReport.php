<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

class SubscriptionsReport
{
    /**
     * @JMS\Type("DMT\Laposta\Api\Entity\ReportSummary")
     * @JMS\SerializedName("report")
     */
    public ReportSummary $summary;

    /**
     * @JMS\Type("array<DMT\Laposta\Api\Entity\Error>")
     *
     * @var array<Error>
     */
    public array $errors;
}
