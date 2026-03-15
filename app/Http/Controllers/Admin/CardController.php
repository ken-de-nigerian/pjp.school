<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneratePinsRequest;
use App\Models\AcademicSession;
use App\Services\NotificationService;
use App\Services\PinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CardController extends Controller
{
    private const USED_PINS_PER_PAGE = 200;

    public function __construct(
        private PinService $pinService,
        private NotificationService $notificationService
    ) {}

    /** Legacy: admin/card — scratch card dashboard with counts */
    public function index(Request $request): View
    {
        $settings = \App\Models\Setting::getCached();
        $session = $settings['session'] ?? '';

        $unusedCount = $this->pinService->countUnused($session);
        $usedCount = $this->pinService->countUsed($session);
        $sessions = AcademicSession::query()->orderBy('year')->get();

        return view('admin.card.index', [
            'unused_count' => $unusedCount,
            'used_count' => $usedCount,
            'settings' => $settings,
            'sessions' => $sessions,
        ]);
    }

    /** Legacy: admin/card/unused-pins */
    public function unusedPins(Request $request): View
    {
        $settings = \App\Models\Setting::getCached();
        $session = $settings['session'] ?? '';

        $unused = $this->pinService->getUnused($session);

        return view('admin.card.unused-pins', [
            'unused' => $unused,
            'settings' => $settings,
        ]);
    }

    /** Legacy: admin/card/used-pins — paginated */
    public function usedPins(Request $request): View
    {
        $settings = \App\Models\Setting::getCached();
        $session = $settings['session'] ?? '';
        $page = (int) $request->query('page', 1);

        $used = $this->pinService->getUsed($session, self::USED_PINS_PER_PAGE, $page);

        return view('admin.card.used-pins', [
            'used' => $used,
            'settings' => $settings,
        ]);
    }

    /** Legacy: admin/card/generate-pins — show form */
    public function showGenerate(Request $request): View
    {
        $settings = \App\Models\Setting::getCached();
        $sessions = AcademicSession::query()->orderBy('year')->get();

        return view('admin.card.generate-pins', [
            'settings' => $settings,
            'sessions' => $sessions,
        ]);
    }

    /** Legacy: admin/card/generate-pins POST — add pins */
    public function generatePins(GeneratePinsRequest $request): JsonResponse
    {
        $session = $request->validated('session');
        $pins = $request->getPins();

        if (empty($pins)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No pins were found on input. Please try again later.',
            ], 422);
        }

        $inserted = $this->pinService->addPins($pins, $session);

        if ($inserted > 0) {
            $adminName = $request->user('admin')->name ?? 'Admin';
            $this->notificationService->add(
                'Pins Added',
                "{$adminName} has generated new pins for {$session} Session"
            );
        }

        return response()->json([
            'status' => $inserted > 0 ? 'success' : 'error',
            'message' => $inserted > 0
                ? "{$session} Session pins have been generated successfully."
                : 'No pins generated. Please try again later.',
        ]);
    }
}
