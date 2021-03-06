<?php

namespace App\Request\Data;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class TimetableLessonData {


    /**
     * @Serializer\Type("string")
     * @Assert\NotBlank()
     * @var string
     */
    private $id;

    /**
     * @Serializer\Type("string")
     * @Assert\NotNull()
     * @var string
     */
    private $week;

    /**
     * @Serializer\Type("int")
     * @Assert\NotNull()
     * @Assert\GreaterThan(0)
     * @var int
     */
    private $day;

    /**
     * @Serializer\Type("int")
     * @Assert\NotNull()
     * @Assert\GreaterThan(0)
     * @var int
     */
    private $lesson;

    /**
     * @Serializer\Type("bool")
     * @Assert\NotNull()
     * @var bool
     */
    private $isDoubleLesson;

    /**
     * @Serializer\Type("string")
     * @var string|null
     */
    private $room;

    /**
     * @Serializer\Type("array<string>")
     * @var string[]
     */
    private $teachers = [ ];

    /**
     * @Serializer\Type("array<string>")
     * @var string[]
     */
    private $grades = [ ];


    /**
     * @Serializer\Type("string")
     * @Assert\NotBlank(allowNull=true)
     * @var string|null
     */
    private $subject;

    /**
     * @return string|null
     */
    public function getId(): ?string {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return TimetableLessonData
     */
    public function setId(?string $id): TimetableLessonData {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getWeek(): ?string {
        return $this->week;
    }

    /**
     * @param string|null $week
     * @return TimetableLessonData
     */
    public function setWeek(?string $week): TimetableLessonData {
        $this->week = $week;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDay(): ?int {
        return $this->day;
    }

    /**
     * @param int|null $day
     * @return TimetableLessonData
     */
    public function setDay(?int $day): TimetableLessonData {
        $this->day = $day;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLesson(): ?int {
        return $this->lesson;
    }

    /**
     * @param int|null $lesson
     * @return TimetableLessonData
     */
    public function setLesson(?int $lesson): TimetableLessonData {
        $this->lesson = $lesson;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDoubleLesson(): bool {
        return $this->isDoubleLesson;
    }

    /**
     * @param bool $isDoubleLesson
     * @return TimetableLessonData
     */
    public function setIsDoubleLesson(bool $isDoubleLesson): TimetableLessonData {
        $this->isDoubleLesson = $isDoubleLesson;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRoom(): ?string {
        return $this->room;
    }

    /**
     * @param string|null $room
     * @return TimetableLessonData
     */
    public function setRoom(?string $room): TimetableLessonData {
        $this->room = $room;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getTeachers(): array {
        return $this->teachers;
    }

    /**
     * @param string[] $teachers
     * @return TimetableLessonData
     */
    public function setTeachers(array $teachers): TimetableLessonData {
        $this->teachers = $teachers;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getGrades(): array {
        return $this->grades;
    }

    /**
     * @param string[] $grades
     * @return TimetableLessonData
     */
    public function setGrades(array $grades): TimetableLessonData {
        $this->grades = $grades;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string {
        return $this->subject;
    }

    /**
     * @param string|null $subject
     * @return TimetableLessonData
     */
    public function setSubject(?string $subject): TimetableLessonData {
        $this->subject = $subject;
        return $this;
    }
}