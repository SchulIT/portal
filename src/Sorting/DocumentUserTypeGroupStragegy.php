<?php

namespace App\Sorting;

use App\Grouping\DocumentUserTypeGroup;

class DocumentUserTypeGroupStragegy implements SortingStrategyInterface {

    /**
     * @param DocumentUserTypeGroup $objectA
     * @param DocumentUserTypeGroup $objectB
     * @return int
     */
    public function compare($objectA, $objectB): int {
        return strcmp($objectA->getUserType()->getValue(), $objectB->getUserType()->getValue());
    }
}