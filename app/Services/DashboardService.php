<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\News;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

/**
 * Replicates legacy admin dashboard data.
 * Same query filters and counts as legacy (Admin::countAllStudents, etc.).
 */
class DashboardService
{
    private const NEWS_PER_PAGE_ADMIN = 6;

    private const NEWS_PER_PAGE_TEACHER = 3;

    public function getAdminCounts(): array
    {
        return [
            'count-all-students' => Student::query()
                ->whereNotIn('class', ['Left', 'Graduated'])
                ->where('status', 2)
                ->count(),
            'count-boarding-students' => Student::query()
                ->where('category', 'Boarding')
                ->whereNotIn('class', ['Left', 'Graduated'])
                ->where('status', 2)
                ->count(),
            'count-day-students' => Student::query()
                ->where('category', 'Day')
                ->whereNotIn('class', ['Left', 'Graduated'])
                ->where('status', 2)
                ->count(),
            'count-subjects' => Subject::query()->count(),
            'count-teachers' => Teacher::query()->count(),
        ];
    }

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

    public function getCachedSettings(): array
    {
        return Setting::getCached();
    }
}
