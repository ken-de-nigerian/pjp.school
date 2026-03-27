<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\DashboardCountsDTO;
use App\Models\News;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

final class DashboardService
{
    private const NEWS_PER_PAGE_ADMIN = 6;

    private const NEWS_PER_PAGE_TEACHER = 6;

    public function getAdminCounts(): DashboardCountsDTO
    {
        return new DashboardCountsDTO(
            countAllStudents: Student::query()
                ->whereNotIn('class', ['Left', 'Graduated'])
                ->where('status', 2)
                ->count(),
            countBoardingStudents: Student::query()
                ->where('category', 'Boarding')
                ->whereNotIn('class', ['Left', 'Graduated'])
                ->where('status', 2)
                ->count(),
            countDayStudents: Student::query()
                ->where('category', 'Day')
                ->whereNotIn('class', ['Left', 'Graduated'])
                ->where('status', 2)
                ->count(),
            countSubjects: Subject::query()->count(),
            countTeachers: Teacher::query()->count(),
        );
    }

    /** @return LengthAwarePaginator<int, News> */
    public function getAdminNewsPaginated(int $page = 1): LengthAwarePaginator
    {
        $query = News::query();

        if (Schema::hasColumn('news', 'created_at')) {
            $query->orderByDesc('created_at');
        } else {
            $query->orderByDesc('date_added');
        }

        return $query->paginate(self::NEWS_PER_PAGE_ADMIN, ['*'], 'page', $page);
    }

    /** @return LengthAwarePaginator<int, News> */
    public function getTeacherNewsPaginated(int $page = 1): LengthAwarePaginator
    {
        $query = News::query();

        if (Schema::hasColumn('news', 'created_at')) {
            $query->orderByDesc('created_at');
        } else {
            $query->orderByDesc('date_added');
        }

        return $query->paginate(self::NEWS_PER_PAGE_TEACHER, ['*'], 'page', $page);
    }

    /** @return array<string, mixed> */
    public function getCachedSettings(): array
    {
        return Setting::getCached();
    }
}
