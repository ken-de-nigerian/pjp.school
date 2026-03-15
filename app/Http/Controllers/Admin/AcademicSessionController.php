<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAcademicSessionRequest;
use App\Http\Requests\UpdateAcademicSessionRequest;
use App\Services\AcademicSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicSessionController extends Controller
{
    public function __construct(
        private readonly AcademicSessionService $sessionService
    ) {}

    /** List all academic sessions (legacy getSessions). */
    public function index(): View
    {
        $sessions = $this->sessionService->list();
        $currentYear = $this->sessionService->getCurrentSessionYear();

        return view('admin.sessions.index', [
            'sessions' => $sessions,
            'currentYear' => $currentYear,
        ]);
    }

    public function create(): View
    {
        return view('admin.sessions.create');
    }

    public function store(StoreAcademicSessionRequest $request): RedirectResponse
    {
        $this->sessionService->create($request->validated('year'));

        return redirect()->route('admin.sessions.index')->with('success', 'Academic session created.');
    }

    public function edit(Request $request, int $id): View|RedirectResponse
    {
        $session = $this->sessionService->find($id);
        if ($session === null) {
            return redirect()->route('admin.sessions.index')->with('error', 'Session not found.');
        }

        return view('admin.sessions.edit', ['session' => $session]);
    }

    public function update(UpdateAcademicSessionRequest $request, int $id): RedirectResponse
    {
        $session = $this->sessionService->find($id);
        if ($session === null) {
            return redirect()->route('admin.sessions.index')->with('error', 'Session not found.');
        }
        $this->sessionService->update($id, $request->validated('year'));

        return redirect()->route('admin.sessions.index')->with('success', 'Academic session updated.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $session = $this->sessionService->find($id);
        if ($session === null) {
            return redirect()->route('admin.sessions.index')->with('error', 'Session not found.');
        }
        $this->sessionService->delete($id);

        return redirect()->route('admin.sessions.index')->with('success', 'Academic session deleted.');
    }

    /** Set this session as current (settings.session = year). */
    public function activate(Request $request, int $id): RedirectResponse
    {
        $ok = $this->sessionService->activate($id);

        if (! $ok) {
            return redirect()->route('admin.sessions.index')->with('error', 'Session not found or could not activate.');
        }

        return redirect()->route('admin.sessions.index')->with('success', 'Session activated as current.');
    }
}
