<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Toggle2FARequest;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = Setting::getCached();
        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }

    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        $this->authorize('update', Setting::class);

        $setting = Setting::query()->first();
        if ($setting) {
            $setting->update($request->validated());
            Setting::clearSettingsCache();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Settings updated.',
        ]);
    }

    public function toggle2FA(Toggle2FARequest $request): JsonResponse
    {
        $admin = $request->user('admin');
        if ($admin) {
            $admin->update(['security' => $request->validated('security')]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Two-factor authentication updated.',
        ]);
    }
}
