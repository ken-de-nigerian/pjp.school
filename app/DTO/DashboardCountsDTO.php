<?php

declare(strict_types=1);

namespace App\DTO;

use App\Support\Coercion;

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
     * @return array<string, int>
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

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            countAllStudents: Coercion::int($data['count-all-students'] ?? 0),
            countBoardingStudents: Coercion::int($data['count-boarding-students'] ?? 0),
            countDayStudents: Coercion::int($data['count-day-students'] ?? 0),
            countSubjects: Coercion::int($data['count-subjects'] ?? 0),
            countTeachers: Coercion::int($data['count-teachers'] ?? 0),
        );
    }
}
