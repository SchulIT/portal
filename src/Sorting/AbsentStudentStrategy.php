<?php

namespace App\Sorting;

use App\Dashboard\AbsentStudent;

class AbsentStudentStrategy implements SortingStrategyInterface {

    private $studentStrategy;

    public function __construct(StudentStrategy $studentStrategy) {
        $this->studentStrategy = $studentStrategy;
    }

    /**
     * @param AbsentStudent $objectA
     * @param AbsentStudent $objectB
     * @return int
     */
    public function compare($objectA, $objectB): int {
        return $this->studentStrategy->compare($objectA->getStudent(), $objectB->getStudent());
    }
}