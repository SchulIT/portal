<?php

namespace App\Import;

use App\Entity\Grade;
use App\Repository\GradeRepositoryInterface;
use App\Repository\SectionRepositoryInterface;
use App\Repository\TransactionalRepositoryInterface;
use App\Request\Data\GradeData;
use App\Request\Data\GradesData;
use App\Utils\ArrayUtils;

class GradesImportStrategy implements ImportStrategyInterface {

    private $repository;
    private $sectionRepository;

    public function __construct(GradeRepositoryInterface $repository, SectionRepositoryInterface $sectionRepository) {
        $this->repository = $repository;
        $this->sectionRepository = $sectionRepository;
    }

    /**
     * @param GradesData $requestData
     * @return array<string, Grade>
     * @throws SectionNotFoundException
     */
    public function getExistingEntities($requestData): array {
        /*$section = $this->sectionRepository->findOneByNumberAndYear($requestData->getSection(), $requestData->getYear());

        if($section === null) {
            throw new SectionNotFoundException($requestData->getSection(), $requestData->getYear());
        }*/

        return ArrayUtils::createArrayWithKeys(
            $this->repository->findAll(),
            function (Grade $grade) {
                return $grade->getExternalId();
            }
        );
    }

    /**
     * @param GradeData $data
     * @param GradesData $requestData
     * @return Grade
     */
    public function createNewEntity($data, $requestData) {
        $grade = (new Grade())
            ->setExternalId($data->getId());

        $this->updateEntity($grade, $data, $requestData);
        return $grade;
    }

    /**
     * @param GradeData $object
     * @param array<string, Grade> $existingEntities
     * @return Grade|null
     */
    public function getExistingEntity($object, array $existingEntities) {
        return $existingEntities[$object->getId()] ?? null;
    }

    /**
     * @param Grade $entity
     * @return int
     */
    public function getEntityId($entity): int {
        return $entity->getId();
    }

    /**
     * @param Grade $entity
     * @param GradesData $requestData
     * @param GradeData $data
     */
    public function updateEntity($entity, $data, $requestData): void {
        $entity->setName($data->getName());
    }

    /**
     * @inheritDoc
     */
    public function persist($entity): void {
        $this->repository->persist($entity);
    }

    /**
     * @param Grade $entity
     * @param GradesData $requestData
     * @return bool
     */
    public function remove($entity, $requestData): bool {
        //$this->repository->remove($entity);
        // never remove grades
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getRepository(): TransactionalRepositoryInterface {
        return $this->repository;
    }

    /**
     * @param GradesData $data
     * @return GradeData[]
     */
    public function getData($data): array {
        return $data->getGrades();
    }

    /**
     * @inheritDoc
     */
    public function getEntityClassName(): string {
        return Grade::class;
    }
}