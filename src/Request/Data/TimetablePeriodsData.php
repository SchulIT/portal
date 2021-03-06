<?php

namespace App\Request\Data;

use App\Validator\UniqueId;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class TimetablePeriodsData {

    /**
     * @Serializer\Type("array<App\Request\Data\TimetablePeriodData>")
     * @Assert\Valid()
     * @UniqueId(propertyPath="id")
     * @var TimetablePeriodData[]
     */
    private $periods = [ ];

    /**
     * @return TimetablePeriodData[]
     */
    public function getPeriods() {
        return $this->periods;
    }

    /**
     * @param TimetablePeriodData[] $periods
     * @return TimetablePeriodsData
     */
    public function setPeriods($periods): TimetablePeriodsData {
        $this->periods = $periods;
        return $this;
    }
}