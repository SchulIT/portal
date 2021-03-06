<?php

namespace App\Import;

use App\Entity\GradeMembership;
use App\Repository\GradeMembershipRepositoryInterface;
use App\Repository\GradeRepositoryInterface;
use App\Repository\SectionRepositoryInterface;
use App\Repository\StudentRepositoryInterface;
use App\Repository\TransactionalRepositoryInterface;
use App\Request\Data\GradeMembershipData;
use App\Request\Data\GradeMembershipsData;

class GradeMembershipImportStrategy implements ReplaceImportStrategyInterface {

    private $membershipRepository;
    private $studentRepository;
    private $gradeRepository;
    private $sectionRepository;

    public function __construct(GradeMembershipRepositoryInterface $membershipRepository, StudentRepositoryInterface $studentRepository,
                                GradeRepositoryInterface $gradeRepository, SectionRepositoryInterface $sectionRepository) {
        $this->membershipRepository = $membershipRepository;
        $this->studentRepository = $studentRepository;
        $this->gradeRepository = $gradeRepository;
        $this->sectionRepository = $sectionRepository;
    }

    /**
     * @inheritDoc
     */
    public function getEntityClassName(): string {
        return GradeMembership::class;
    }

    /**
     * @param GradeMembershipsData $data
     * @return GradeMembershipData[]
     */
    public function getData($data): array {
        return $data->getMemberships();
    }

    public function getRepository(): TransactionalRepositoryInterface {
        return $this->membershipRepository;
    }

    /**
     * @param GradeMembershipsData $data
     * @throws SectionNotFoundException
     */
    public function removeAll($data): void {
        $section = $this->sectionRepository->findOneByNumberAndYear($data->getSection(), $data->getYear());

        if($section === null) {
            throw new SectionNotFoundException($data->getSection(), $data->getYear());
        }

        $this->membershipRepository->removeAll($section);
    }

    /**
     * @param GradeMembershipData $data
     * @param GradeMembershipsData $requestData
     * @throws EntityIgnoredException
     * @throws SectionNotFoundException
     */
    public function persist($data, $requestData): void {
        $student = $this->studentRepository->findOneByExternalId($data->getStudent());

        if($student === null) {
            throw new EntityIgnoredException($data, sprintf('Student with ID "%s" was not found.', $data->getStudent()));
        }

        $grade = $this->gradeRepository->findOneByExternalId($data->getGrade());

        if($grade === null) {
            throw new EntityIgnoredException($data, sprintf('Grade with ID "%s" was not found.', $data->getGrade()));
        }

        $section = $this->sectionRepository->findOneByNumberAndYear($requestData->getSection(), $requestData->getYear());

        if($section === null) {
            throw new SectionNotFoundException($requestData->getSection(), $requestData->getYear());
        }

        $membership = (new GradeMembership())
            ->setGrade($grade)
            ->setStudent($student)
            ->setSection($section);

        $this->membershipRepository->persist($membership);
    }
}