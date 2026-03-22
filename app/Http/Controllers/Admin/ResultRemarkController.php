<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\ResultRemarkServiceContract;
use App\DTO\StoreResultRemarkDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResultRemarkRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class ResultRemarkController extends Controller
{
    use AuthorizesAdminPermission;

    public function __construct(
        private readonly ResultRemarkServiceContract $resultRemarkService
    ) {}

    public function storeOrUpdate(StoreResultRemarkRequest $request): JsonResponse
    {
        $this->authorizePermission('view_published_results');

        $validated = $request->validated();

        $dto = new StoreResultRemarkDTO(
            regNumber: trim((string) $validated['reg_number']),
            class: trim((string) $validated['class']),
            term: trim((string) $validated['term']),
            session: trim((string) $validated['session']),
            remark: isset($validated['remark']) ? trim((string) $validated['remark']) : null,
        );

        try {
            $this->resultRemarkService->storeOrUpdate($dto);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() ?: 'Could not save remark.',
            ], 422);
        }

        $savedRemark = $dto->remark !== null && $dto->remark !== '' ? $dto->remark : null;

        return response()->json([
            'status' => 'success',
            'message' => 'Principal remark saved.',
            'remark' => $savedRemark,
        ]);
    }
}
