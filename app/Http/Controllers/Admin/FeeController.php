<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\FeeServiceContract;
use App\Enums\Term;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeeRequest;
use App\Http\Requests\UpdateFeeRequest;
use App\Models\Fee;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class FeeController extends Controller
{
    public function __construct(
        private readonly FeeServiceContract $feeService
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Fee::class);

        $settings = Setting::getCached();
        $term = $request->query('term', $settings['term'] ?? '');
        $session = $request->query('session', $settings['session'] ?? '');

        $fees = $this->feeService->listForAdminFilters(
            is_string($term) && $term !== '' ? $term : null,
            is_string($session) && $session !== '' ? $session : null,
        );

        return view('admin.fees.index', [
            'fees' => $fees,
            'filterTerm' => $term,
            'filterSession' => $session,
            'termOptions' => Term::labels(),
        ]);
    }

    public function store(StoreFeeRequest $request): JsonResponse
    {
        Gate::authorize('create', Fee::class);

        $data = $request->validated();
        if (! array_key_exists('is_active', $data)) {
            $data['is_active'] = true;
        }

        $fee = Fee::query()->create($data);

        return response()->json([
            'status' => 'success',
            'message' => __('Fee saved.'),
            'fee' => $this->feeToArray($fee->fresh()),
        ]);
    }

    public function update(UpdateFeeRequest $request, Fee $fee): JsonResponse
    {
        Gate::authorize('update', $fee);

        $fee->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => __('Fee updated.'),
            'fee' => $this->feeToArray($fee->fresh()),
        ]);
    }

    public function destroy(Fee $fee): JsonResponse
    {
        Gate::authorize('delete', $fee);

        $fee->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('Fee removed.'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function feeToArray(Fee $fee): array
    {
        return [
            'id' => $fee->id,
            'title' => $fee->title,
            'description' => $fee->description,
            'amount' => (string) $fee->amount,
            'category' => $fee->category->value,
            'term' => $fee->term,
            'session' => $fee->session,
            'is_active' => $fee->is_active,
        ];
    }
}
