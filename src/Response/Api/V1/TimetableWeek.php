<?php

namespace App\Response\Api\V1;

use App\Entity\TimetableWeek as TimetableWeekEntity;
use JMS\Serializer\Annotation as Serializer;

class TimetableWeek {

    use UuidTrait;

    /**
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     * @var string
     */
    private $name;

    /**
     * @Serializer\SerializedName("week_mod")
     * @Serializer\Type("int")
     * @var int[]
     */
    private $weeks = [ ];

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TimetableWeek
     */
    public function setName(string $name): TimetableWeek {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getWeeks(): array {
        return $this->weeks;
    }

    /**
     * @param int[] $weeks
     * @return TimetableWeek
     */
    public function setWeeks(array $weeks): TimetableWeek {
        $this->weeks = $weeks;
        return $this;
    }

    public static function fromEntity(TimetableWeekEntity $entity): self {
        return (new self())
            ->setUuid($entity->getUuid())
            ->setName($entity->getDisplayName())
            ->setWeeks($entity->getWeeksAsIntArray());
    }
}