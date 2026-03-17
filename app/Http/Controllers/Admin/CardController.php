<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneratePinsRequest;
use App\Models\Setting;
use App\Services\NotificationService;
use App\Services\PinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Random\RandomException;

class CardController extends Controller
{
    private const USED_PINS_PER_PAGE = 50;

    private const UNUSED_PINS_PER_PAGE = 50;

    public function __construct(
        private readonly PinService $pinService,
        private readonly NotificationService $notificationService
    ) {}

    public function index(): View
    {
        $settings = Setting::getCached();
        $session = $settings['session'] ?? '';

        $unusedCount = $this->pinService->countUnused($session);
        $usedCount = $this->pinService->countUsed($session);

        return view('admin.card.index', [
            'unused_count' => $unusedCount,
            'used_count' => $usedCount,
            'settings' => $settings
        ]);
    }

    public function unusedPins(Request $request): View
    {
        $settings = Setting::getCached();
        $session = $settings['session'] ?? '';
        $page = (int) $request->query('page', 1);

        $unused = $this->pinService->getUnusedPaginated($session, self::UNUSED_PINS_PER_PAGE, $page);

        return view('admin.card.unused-pins', [
            'unused' => $unused,
            'settings' => $settings,
        ]);
    }

    public function unusedPinsPdf(): View
    {
        $settings = Setting::getCached();
        $session = $settings['session'] ?? '';
        $unused = $this->pinService->getUnused($session);

        return view('admin.card.unused-pins-pdf', [
            'unused' => $unused,
            'session' => $session,
        ]);
    }

    public function usedPins(Request $request): View
    {
        $settings = Setting::getCached();
        $session = $settings['session'] ?? '';
        $page = (int) $request->query('page', 1);

        $used = $this->pinService->getUsed($session, self::USED_PINS_PER_PAGE, $page);

        return view('admin.card.used-pins', [
            'used' => $used,
            'settings' => $settings,
        ]);
    }

    /**
     * @throws RandomException
     */
    public function generatePins(GeneratePinsRequest $request): JsonResponse
    {
        $session = $request->validated('session');
        $count = (int) $request->validated('count');

        $pins = $this->pinService->generatePinValues($count);

        if (empty($pins)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No pins to generate. Specify a count (1–500) or provide pins.',
            ], 422);
        }

        $inserted = $this->pinService->addPins($pins, $session);

        if ($inserted > 0) {
            $adminName = $request->user('admin')->name ?? 'Admin';
            $this->notificationService->add(
                'Pins Added',
                "$adminName has generated new pins for $session Session"
            );
        }

        return response()->json([
            'status' => $inserted > 0 ? 'success' : 'error',
            'message' => $inserted > 0
                ? "$session Session pins have been generated successfully."
                : 'No pins generated. Please try again later.',
        ]);
    }
}
