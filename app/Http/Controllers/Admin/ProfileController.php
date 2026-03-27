<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeAdminPasswordRequest;
use App\Http\Requests\UpdateAdminProfileRequest;
use App\Http\Requests\UploadAdminAvatarRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class ProfileController extends Controller
{
    public function show(): RedirectResponse
    {
        return redirect()->route('admin.settings.index');
    }

    public function update(UpdateAdminProfileRequest $request): JsonResponse
    {
        $admin = $request->user('admin');
        if (! $admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.',
            ], 403);
        }

        $admin->update([
            'name' => $request->validated('fullName'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('formattedPhone'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Your profile information has been updated successfully.',
            'redirect' => 'admin/settings',
        ]);
    }

    public function changePassword(ChangeAdminPasswordRequest $request): JsonResponse
    {
        $admin = $request->user('admin');
        if (! $admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.',
            ], 403);
        }

        if (! Hash::check($request->input('oldPassword'), $admin->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your current password does not match our records. Please try again.',
            ]);
        }

        $admin->update([
            'password' => Hash::make($request->input('password')),
            'password_change_date' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Your password has been successfully changed.',
            'redirect' => 'admin/settings',
        ]);
    }

    public function uploadAvatar(UploadAdminAvatarRequest $request): JsonResponse
    {
        $admin = $request->user('admin');
        if (! $admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.',
            ], 403);
        }

        $file = $request->file('photoimg');
        $ext = $file->getClientOriginalExtension();
        $filename = Str::random(12).'.'.strtolower($ext);

        $path = $file->storeAs('staffs', $filename, 'public');
        if (! $path) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to save the image. Please try again.',
            ]);
        }

        $admin->update(['profileImage' => $filename]);

        return response()->json([
            'status' => 'success',
        ]);
    }
}
