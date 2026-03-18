<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Toggle2FARequest;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = Setting::getCached();

        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        $this->authorize('update', Setting::class);

        $setting = Setting::query()->first();
        if ($setting) {
            $data = $request->validated();
            $data['segment'] = config('school.no_segment', 'No Segment');
            DB::transaction(function () use ($setting, $data) {
                $setting->update($data);
            });
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
