<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\ChecklistServiceContract;
use App\Enums\Term;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChecklistRequest;
use App\Http\Requests\UpdateChecklistRequest;
use App\Models\Checklist;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Throwable;

final class ChecklistController extends Controller
{
    public function __construct(
        private readonly ChecklistServiceContract $checklistService
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Checklist::class);

        $settings = Setting::getCached();
        $term = $request->query('term', $settings['term'] ?? '');
        $session = $request->query('session', $settings['session'] ?? '');

        $checklists = $this->checklistService->listForAdminFilters(
            is_string($term) && $term !== '' ? $term : null,
            is_string($session) && $session !== '' ? $session : null,
        );

        return view('admin.checklists.index', [
            'checklists' => $checklists,
            'filterTerm' => $term,
            'filterSession' => $session,
            'termOptions' => Term::labels(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(StoreChecklistRequest $request): JsonResponse
    {
        Gate::authorize('create', Checklist::class);

        $data = $request->validated();
        if (! array_key_exists('is_active', $data)) {
            $data['is_active'] = true;
        }

        $position = $data['position'] ?? null;
        unset($data['position']);

        $checklist = DB::transaction(function () use ($data, $position): Checklist {
            if ($position === null) {
                $max = (int) Checklist::query()
                    ->where('term', $data['term'])
                    ->where('session', $data['session'])
                    ->max('position');
                $data['position'] = $max + 1;
            } else {
                $data['position'] = (int) $position;
            }

            return Checklist::query()->create($data);
        });

        return response()->json([
            'status' => 'success',
            'message' => __('Checklist item saved.'),
            'checklist' => $this->checklistToArray($checklist->fresh() ?? $checklist),
        ]);
    }

    public function update(UpdateChecklistRequest $request, Checklist $checklist): JsonResponse
    {
        Gate::authorize('update', $checklist);

        $data = $request->validated();
        if (array_key_exists('position', $data)) {
            $data['position'] = (int) $data['position'];
        }

        $checklist->update($data);

        return response()->json([
            'status' => 'success',
            'message' => __('Checklist item updated.'),
            'checklist' => $this->checklistToArray($checklist->fresh() ?? $checklist),
        ]);
    }

    public function destroy(Checklist $checklist): JsonResponse
    {
        Gate::authorize('delete', $checklist);

        $checklist->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('Checklist item removed.'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function checklistToArray(Checklist $checklist): array
    {
        return [
            'id' => $checklist->id,
            'title' => $checklist->title,
            'term' => $checklist->term,
            'session' => $checklist->session,
            'is_active' => $checklist->is_active,
            'position' => $checklist->position,
        ];
    }
}
