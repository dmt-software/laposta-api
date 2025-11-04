<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

class SubscriptionsReport
{
    #[JMS\Type(ReportSummary::class)]
    #[JMS\SerializedName('report')]
    public ReportSummary $summary;

    /** @var array<Error> */
    #[JMS\Type('array<DMT\Laposta\Api\Entity\Error>')]
    public array $errors;
}
