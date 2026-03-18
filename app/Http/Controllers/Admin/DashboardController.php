<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index(Request $request): View
    {
        $admin = $request->user('admin');
        $admin->load('role');

        $countsDto = $this->dashboardService->getAdminCounts();
        $counts = $countsDto->toArray();
        $page = (int) $request->query('page', 1);
        $newsPaginator = $this->dashboardService->getAdminNewsPaginated($page);
        $settings = $this->dashboardService->getCachedSettings();

        return view('admin.dashboard', [
            'role' => $admin->role,
            'counts' => $counts,
            'count_all_students' => $countsDto->countAllStudents,
            'count_boarding_students' => $countsDto->countBoardingStudents,
            'count_day_students' => $countsDto->countDayStudents,
            'count_subjects' => $countsDto->countSubjects,
            'count_teachers' => $countsDto->countTeachers,
            'get_news' => $newsPaginator->items(),
            'news' => $newsPaginator,
            'currentPage' => $newsPaginator->currentPage(),
            'itemsPerPage' => $newsPaginator->perPage(),
            'totalItems' => $newsPaginator->total(),
            'totalPages' => $newsPaginator->lastPage(),
            'offset' => ($newsPaginator->currentPage() - 1) * $newsPaginator->perPage(),
            'settings' => $settings,
        ]);
    }
}
