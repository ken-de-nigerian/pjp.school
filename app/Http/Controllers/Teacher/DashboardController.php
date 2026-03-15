<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    /**
     * Replicates legacy teacher dashboard: user, paginated news (3 per page), pagination vars.
     */
    public function index(Request $request): View
    {
        $user = $request->user('teacher');
        $page = (int) $request->query('page', 1);
        $newsPaginator = $this->dashboardService->getTeacherNewsPaginated($page);

        return view('teacher.dashboard', [
            'user' => $user,
            'get_news' => $newsPaginator->items(),
            'news' => $newsPaginator,
        ]);
    }
}
