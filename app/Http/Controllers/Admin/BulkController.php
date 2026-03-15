<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BulkController extends Controller
{
    /** GET admin/bulk — bulk SMS page (Phase 6). */
    public function index(): View
    {
        return view('admin.bulk.index');
    }

    /** POST admin/bulk/send — send bulk SMS. Legacy-compatible; integrate with SMS provider when configured. */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'recipients' => 'nullable|array',
            'recipients.*' => 'string|max:50',
            'message' => 'required|string|max:1000',
            'group' => 'nullable|string|max:100',
        ]);

        // Stub: when SMS provider is configured, dispatch or send here
        $message = $validated['message'];
        $count = count($validated['recipients'] ?? []);

        return response()->json([
            'status' => 'success',
            'message' => 'Bulk SMS request accepted. ' . ($count > 0 ? $count . ' recipient(s).' : 'Configure recipients and SMS provider in settings.'),
        ]);
    }
}
