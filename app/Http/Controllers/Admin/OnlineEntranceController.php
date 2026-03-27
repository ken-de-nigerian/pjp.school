<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entrance;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class OnlineEntranceController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Entrance::class);

        $applicants = Entrance::query()->ordered()->get();

        return view('admin.online-entrance.index', [
            'applicants' => $applicants,
        ]);
    }

    public function applicantsPdf(): View
    {
        Gate::authorize('viewAny', Entrance::class);

        $applicants = Entrance::query()->ordered()->get();

        return view('admin.online-entrance.applicants-pdf', [
            'applicants' => $applicants,
        ]);
    }

    public function show(Entrance $entrance): View
    {
        Gate::authorize('viewAny', Entrance::class);

        return view('admin.online-entrance.show', [
            'applicant' => $entrance,
        ]);
    }
}
