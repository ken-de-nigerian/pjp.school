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
use App\Support\Coercion;
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
        $term = Coercion::string($request->query('term', Coercion::string($settings['term'] ?? '')));
        $session = Coercion::string($request->query('session', Coercion::string($settings['session'] ?? '')));

        $checklists = $this->checklistService->listForAdminFilters(
            $term !== '' ? $term : null,
            $session !== '' ? $session : null,
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

        $data = Coercion::stringKeyedMap($request->validated());
        if (! array_key_exists('is_active', $data)) {
            $data['is_active'] = true;
        }

        $position = $data['position'] ?? null;
        unset($data['position']);

        $checklist = DB::transaction(function () use ($data, $position): Checklist {
            if ($position === null) {
                $max = Coercion::int(Checklist::query()
                    ->where('term', Coercion::string($data['term'] ?? ''))
                    ->where('session', Coercion::string($data['session'] ?? ''))
                    ->max('position'));
                $data['position'] = $max + 1;
            } else {
                $data['position'] = Coercion::int($position);
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

        $data = Coercion::stringKeyedMap($request->validated());
        if (array_key_exists('position', $data)) {
            $data['position'] = Coercion::int($data['position']);
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
