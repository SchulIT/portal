<?php

namespace App\Timetable;

use App\Entity\Subject;
use App\Entity\TimetableLesson as TimetableLessonEntity;

/**
 * This helper filters lessons which are allowed to be visible due to their subjects.
 */
class TimetableFilter {

    /**
     * @param TimetableLessonEntity[] $lessons
     * @param \Closure $predicate
     * @return array
     */
    private function filter(array $lessons, \Closure $predicate) {
        $result = [ ];

        foreach($lessons as $lesson) {
            if($lesson->getTuition() !== null && $lesson->getTuition()->getSubject() !== null) {
                if($predicate($lesson->getTuition()->getSubject())) {
                    $result[] = $lesson;
                }
            } else if($lesson->getSubject() !== null) {
                if($predicate($lesson->getSubject()) === true) {
                    $result[] = $lesson;
                }
            } else {
                // Subject not provided
                $result[] = $lesson;
            }
        }

        return $result;
    }

    public function filterTeacherLessons(array $lessons) {
        return $this->filter($lessons, function(Subject $subject) {
            return $subject->isVisibleTeachers();
        });
    }

    public function filterStudentLessons(array $lessons) {
        return $this->filter($lessons, function(Subject $subject) {
            return $subject->isVisibleStudents();
        });
    }

    public function filterGradeLessons(array $lessons) {
        return $this->filter($lessons, function(Subject $subject) {
            return $subject->isVisibleGrades();
        });
    }

    public function filterRoomLessons(array $lessons) {
        return $this->filter($lessons, function(Subject $subject) {
            return $subject->isVisibleRooms();
        });
    }

    public function filterSubjectsLessons(array $lessons) {
        return $this->filter($lessons, function(Subject $subject) {
            return $subject->isVisibleSubjects();
        });
    }
}