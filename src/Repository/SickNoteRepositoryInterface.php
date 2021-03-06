<?php

namespace App\Repository;

use App\Entity\Grade;
use App\Entity\SickNote;
use App\Entity\Student;
use App\Entity\User;
use DateTime;

interface SickNoteRepositoryInterface {
    /**
     * @param User $user
     * @return SickNote[]
     */
    public function findByUser(User $user): array;

    /**
     * Returns all sick notes for the given students
     *
     * @param Student[] $students
     * @param DateTime|null $date
     * @param int|null $lesson
     * @return SickNote[]
     */
    public function findByStudents(array $students, ?DateTime $date = null, ?int $lesson = null): array;

    /**
     * @param Grade $grade
     * @param DateTime|null $date
     * @return SickNote[]
     */
    public function findByGrade(Grade $grade, ?DateTime $date = null): array;

    /**
     * @param DateTime|null $date
     * @return SickNote[]
     */
    public function findAll(?DateTime $date = null): array;

    /**
     * @param DateTime $threshold
     * @return int Number of removed sick notes
     */
    public function removeExpired(DateTime $threshold): int;

    public function persist(SickNote $note): void;

    public function remove(SickNote $note): void;
}