<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Traits\TeacherScope;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    use TeacherScope;

    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $page = (int) $request->query('page', 1);
        $newsPaginator = $this->dashboardService->getTeacherNewsPaginated($page);

        $canOperateActiveTeacherFeatures = $this->teacherPolicyAllows('operateActiveTeacherFeatures');

        return view('teacher.dashboard', [
            'user' => $user,
            'get_news' => $newsPaginator->items(),
            'news' => $newsPaginator,
            'canOperateActiveTeacherFeatures' => $canOperateActiveTeacherFeatures,
        ]);
    }
}
