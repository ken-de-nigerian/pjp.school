<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NotificationsController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Notification::class);

        $notifications = Notification::query()
            ->orderByDesc('date_added')
            ->paginate(15);

        return view('admin.notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    /** Delete single notification. Legacy: requests/delete_notifications (bulk); single delete for UI. */
    public function destroy(Request $request, Notification $notification): JsonResponse|RedirectResponse
    {
        Gate::authorize('viewAny', Notification::class);

        $notification->delete();

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Notification deleted.']);
        }

        return redirect()->route('admin.notifications.index')->with('success', 'Notification deleted.');
    }

    /** Clear all notifications. Legacy: POST requests/delete_notifications. */
    public function destroyAll(Request $request): JsonResponse|RedirectResponse
    {
        Gate::authorize('viewAny', Notification::class);

        $deleted = Notification::query()->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $deleted > 0 ? 'success' : 'error',
                'message' => $deleted > 0 ? 'Notifications has been cleared successfully.' : 'Unable to clear notifications. Please try again.',
            ]);
        }

        return redirect()->route('admin.notifications.index')
            ->with($deleted > 0 ? 'success' : 'error', $deleted > 0 ? 'Notifications cleared successfully.' : 'Unable to clear notifications.');
    }
}
