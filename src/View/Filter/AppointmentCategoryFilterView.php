<?php

namespace App\View\Filter;

use App\Entity\AppointmentCategory;

class AppointmentCategoryFilterView implements FilterViewInterface {

    /** @var AppointmentCategory[] */
    private $categories;

    /** @var AppointmentCategory|null */
    private $currentCategory;

    public function __construct(array $categories, ?AppointmentCategory $currentCategory) {
        $this->categories = $categories;
        $this->currentCategory = $currentCategory;
    }

    /**
     * @return AppointmentCategory[]
     */
    public function getCategories(): array {
        return $this->categories;
    }

    /**
     * @return AppointmentCategory|null
     */
    public function getCurrentCategory(): ?AppointmentCategory {
        return $this->currentCategory;
    }

    public function isEnabled(): bool {
        return count($this->categories) > 0;
    }
}