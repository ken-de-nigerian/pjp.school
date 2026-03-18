<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Structured dashboard counts for admin dashboard.
 * Use toArray() when passing to Blade to preserve existing view variable shape.
 */
final readonly class DashboardCountsDTO
{
    public function __construct(
        public int $countAllStudents,
        public int $countBoardingStudents,
        public int $countDayStudents,
        public int $countSubjects,
        public int $countTeachers,
    ) {}

    /**
     * Array shape expected by admin. Dashboard view (keys with hyphens).
     */
    public function toArray(): array
    {
        return [
            'count-all-students' => $this->countAllStudents,
            'count-boarding-students' => $this->countBoardingStudents,
            'count-day-students' => $this->countDayStudents,
            'count-subjects' => $this->countSubjects,
            'count-teachers' => $this->countTeachers,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            countAllStudents: (int) ($data['count-all-students'] ?? 0),
            countBoardingStudents: (int) ($data['count-boarding-students'] ?? 0),
            countDayStudents: (int) ($data['count-day-students'] ?? 0),
            countSubjects: (int) ($data['count-subjects'] ?? 0),
            countTeachers: (int) ($data['count-teachers'] ?? 0),
        );
    }
}
