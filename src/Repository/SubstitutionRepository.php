<?php

namespace App\Repository;

use App\Entity\Grade;
use App\Entity\Room;
use App\Entity\StudyGroup;
use App\Entity\StudyGroupType;
use App\Entity\Substitution;
use App\Entity\Teacher;
use App\Utils\ArrayUtils;
use DateTime;
use Doctrine\ORM\QueryBuilder;

class SubstitutionRepository extends AbstractTransactionalRepository implements SubstitutionRepositoryInterface {

    /**
     * @param int $id
     * @return Substitution|null
     */
    public function findOneById(int $id): ?Substitution {
        return $this->em->getRepository(Substitution::class)
            ->findOneBy([
                'id' => $id
            ]);
    }

    /**
     * @param string $externalId
     * @return Substitution|null
     */
    public function findOneByExternalId(string $externalId): ?Substitution {
        return $this->em->getRepository(Substitution::class)
            ->findOneBy([
                'externalId' => $externalId
            ]);
    }

    /**
     * @inheritDoc
     */
    public function findAll() {
        return $this->getDefaultQueryBuilder(null)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param DateTime $date
     * @return Substitution[]
     */
    public function findAllByDate(DateTime $date, bool $excludeNonStudentSubstitutions = false) {
        if($excludeNonStudentSubstitutions === false) {
            return $this->getDefaultQueryBuilder($date)
                ->getQuery()
                ->getResult();
        }

        $qbInner = $this->em->createQueryBuilder();
        $qbInner->select('sInner.id')
            ->from(Substitution::class, 'sInner')
            ->leftJoin('sInner.studyGroups', 'sgInner')
            ->leftJoin('sInner.replacementStudyGroups', 'rsgInner');

        $qbInner->where(
            $qbInner->expr()->andX(
                $qbInner->expr()->isNull('sgInner.id'),
                $qbInner->expr()->isNull('rsgInner.id')
            )
        );

        $qb = $this->getDefaultQueryBuilder($date);
        $qb->andWhere(
            $qb->expr()->notIn('s.id', $qbInner->getDQL())
        );

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Substitution $substitution
     */
    public function persist(Substitution $substitution): void {
        $this->em->persist($substitution);
        $this->flushIfNotInTransaction();
    }

    /**
     * @param Substitution $substitution
     */
    public function remove(Substitution $substitution): void {
        $this->em->remove($substitution);
        $this->flushIfNotInTransaction();
    }

    /**
     * @inheritDoc
     */
    public function findAllForStudyGroups(array $studyGroups, ?\DateTime $date = null) {
        $ids = array_map(function(StudyGroup $studyGroup) {
            return $studyGroup->getId();
        }, $studyGroups);

        /** @var StudyGroup|null $gradeStudyGroup */
        $gradeStudyGroup = ArrayUtils::first($studyGroups, function(StudyGroup $studyGroup) {
            return $studyGroup->getType()->equals(StudyGroupType::Grade());
        });
        /** @var Grade|null $grade */
        $grade = $gradeStudyGroup != null ? $gradeStudyGroup->getGrades()->first() : null;
        $gradeId = $grade !== null ? $grade->getId() : null;

        $qbInner = $this->em->createQueryBuilder();
        $qbInner->select('sInner.id')
            ->from(Substitution::class, 'sInner')
            ->leftJoin('sInner.studyGroups', 'sgInner')
            ->leftJoin('sInner.replacementStudyGroups', 'rsgInner')
            ->leftJoin('sInner.replacementGrades', 'rgInner');

        if($gradeId !== null) {
            $qbInner->where(
                $qbInner->expr()->orX(
                    $qbInner->expr()->in('sgInner.id', ':ids'),
                    $qbInner->expr()->in('rsgInner.id', ':ids'),
                    $qbInner->expr()->eq('rgInner.id', ':gradeId')
                )
            );
        } else {
            $qbInner->where(
                $qbInner->expr()->orX(
                    $qbInner->expr()->in('sgInner.id', ':ids'),
                    $qbInner->expr()->in('rsgInner.id', ':ids')
                )
            );
        }

        $qb = $this->getDefaultQueryBuilder($date);
        $qb->andWhere(
            $qb->expr()->in('s.id', $qbInner->getDQL())
        );
        $qb->setParameter('ids', $ids);

        if($gradeId !== null) {
            $qb->setParameter('gradeId', $gradeId);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @inheritDoc
     */
    public function findAllForTeacher(Teacher $teacher, ?\DateTime $date = null) {
        $qbInner = $this->em->createQueryBuilder();
        $qbInner->select('sInner.id')
            ->from(Substitution::class, 'sInner')
            ->leftJoin('sInner.teachers', 'tInner')
            ->leftJoin('sInner.replacementTeachers', 'rtInner');

        $regExp = '[[:<:]]' . $teacher->getAcronym() . '[[:>:]]';

        $qbInner->where(
            $qbInner->expr()->orX(
                'tInner.id = :id',
                'rtInner.id = :id',
                'REGEXP(sInner.remark, :regexp) = true'
            )
        );

        $qb = $this->getDefaultQueryBuilder($date);
        $qb->andWhere(
            $qb->expr()->in('s.id', $qbInner->getDQL())
        );
        $qb->setParameter('id', $teacher->getId());
        $qb->setParameter('regexp', $regExp);

        return $qb->getQuery()->getResult();
    }

    /**
     * @inheritDoc
     */
    public function findAllForGrade(Grade $grade, ?\DateTime $date = null) {
        $qbInner = $this->em->createQueryBuilder();
        $qbInner->select('sInner.id')
            ->from(Substitution::class, 'sInner')
            ->leftJoin('sInner.studyGroups', 'sgInner')
            ->leftJoin('sInner.replacementStudyGroups', 'rsgInner')
            ->leftJoin('sgInner.grades', 'sggInner')
            ->leftJoin('rsgInner.grades', 'rsggInner')
            ->leftJoin('sInner.replacementGrades', 'rgInner');

        $qbInner->where(
            $qbInner->expr()->orX(
                $qbInner->expr()->eq('sggInner.id', ':id'),
                $qbInner->expr()->eq('rsggInner.id', ':id'),
                $qbInner->expr()->eq('rgInner.id', ':id')
            )
        );

        $qb = $this->getDefaultQueryBuilder($date);
        $qb->andWhere(
            $qb->expr()->in('s.id', $qbInner->getDQL())
        );
        $qb->setParameter('id', $grade->getId());

        return $qb->getQuery()->getResult();
    }

    /**
     * @inheritDoc
     */
    public function findAllForRooms(array $rooms, ?DateTime $date): array {
        $roomIds = array_filter(array_map(function(Room $room) {
            return $room->getId();
        }, $rooms), function($input) {
            return !empty($input);
        });

        $qb = $this->getDefaultQueryBuilder($date);
        $qb->leftJoin('s.replacementRooms', 'r')
            ->leftJoin('s.rooms', 'rr')
            ->andWhere(
            $qb->expr()->orX(
                $qb->expr()->in('r.id', ':roomIds'),
                $qb->expr()->in('rr.id', ':roomIds')
            )
        );
        $qb->setParameter('roomIds', $roomIds);

        return $qb->getQuery()->getResult();
    }

    private function getDefaultQueryBuilder(\DateTime $date = null): QueryBuilder {
        $qb = $this->em->createQueryBuilder();

        $qb->select(['s', 't', 'rt', 'sg', 'rsg', 'rg'])
            ->from(Substitution::class, 's')
            ->leftJoin('s.teachers', 't')
            ->leftJoin('s.replacementTeachers', 'rt')
            ->leftJoin('s.studyGroups', 'sg')
            ->leftJoin('s.replacementStudyGroups', 'rsg')
            ->leftJoin('s.replacementGrades', 'rg')
            ->orderBy('s.date', 'asc')
            ->orderBy('s.lessonStart', 'asc');

        if($date !== null) {
            $qb
                ->where('s.date = :date')
                ->setParameter('date', $date);
        }

        return $qb;
    }

    /*
     * TODO: The following methods need improvement -> count in database!
     */
    public function countAllByDate(DateTime $date): int {
        return count($this->findAllByDate($date));
    }

    public function countAllForStudyGroups(array $studyGroups, ?DateTime $date = null): int {
        return count($this->findAllForStudyGroups($studyGroups, $date));
    }

    public function countAllForTeacher(Teacher $teacher, ?DateTime $date = null): int {
        return count($this->findAllForTeacher($teacher, $date));
    }

    public function countAllForGrade(Grade $grade, ?DateTime $date = null): int {
        return count($this->findAllForGrade($grade, $date));
    }
}