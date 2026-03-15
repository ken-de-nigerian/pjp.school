<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entrance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OnlineEntranceController extends Controller
{
    /**
     * List entrance examination applicants (Phase 4D).
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Entrance::class);

        $applicants = Entrance::query()->ordered()->get();

        return view('admin.online-entrance.index', [
            'applicants' => $applicants,
        ]);
    }

    /**
     * Print-friendly / PDF view of all applicants (open in new tab, then Print / Save as PDF).
     */
    public function applicantsPdf(): View
    {
        Gate::authorize('viewAny', Entrance::class);

        $applicants = Entrance::query()->ordered()->get();

        return view('admin.online-entrance.applicants-pdf', [
            'applicants' => $applicants,
        ]);
    }

    /**
     * View a single entrance application (legacy parity: view application).
     */
    public function show(int $id): View|RedirectResponse
    {
        $applicant = Entrance::query()->find($id);
        if ($applicant === null) {
            return redirect()->route('admin.online_entrance.index')->with('error', 'Application not found.');
        }
        Gate::authorize('viewAny', Entrance::class);

        return view('admin.online-entrance.show', [
            'applicant' => $applicant,
        ]);
    }
}
