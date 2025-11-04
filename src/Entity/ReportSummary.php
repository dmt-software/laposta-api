<?php

namespace DMT\Laposta\Api\Entity;

use JMS\Serializer\Annotation as JMS;

class ReportSummary
{
    #[JMS\SerializedName('provided_count')]
    #[JMS\Type('int')]
    public int $provided = 0;

    #[JMS\SerializedName('errors_count')]
    #[JMS\Type('int')]
    public int $errors = 0;

    #[JMS\SerializedName('skipped_count')]
    #[JMS\Type('int')]
    public int $skipped = 0;

    #[JMS\SerializedName('edited_count')]
    #[JMS\Type('int')]
    public int $edited = 0;

    #[JMS\SerializedName('added_count')]
    #[JMS\Type('int')]
    public int $added = 0;
}
