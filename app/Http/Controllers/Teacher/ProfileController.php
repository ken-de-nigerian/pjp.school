<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class ProfileController extends Controller
{
    public function show(): View
    {
        $teacher = auth('teacher')->user();
        if (! $teacher) {
            abort(403, 'Unauthorized.');
        }

        return view('teacher.profile.index', [
            'user' => $teacher,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $teacher = auth('teacher')->user();
        if (! $teacher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.',
            ], 403);
        }

        $v = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'othername' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('user', 'email')->ignore($teacher->userId, 'userId'),
            ],
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
        ]);

        $teacher->update($v);

        return response()->json([
            'status' => 'success',
            'message' => 'Your profile information has been updated successfully.',
        ]);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $teacher = auth('teacher')->user();
        if (! $teacher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.',
            ], 403);
        }

        $v = $request->validate([
            'oldPassword' => 'required|string|min:6',
            'password' => 'required|string|min:8',
            'confirmPassword' => 'required|string|min:8|same:password',
        ]);

        if (! Hash::check($v['oldPassword'], (string) ($teacher->password ?? ''))) {
            return response()->json([
                'message' => 'Your current password does not match our records. Please try again.',
                'errors' => ['oldPassword' => ['Your current password does not match our records. Please try again.']],
            ], 422);
        }

        $teacher->update([
            'password' => Hash::make($v['password']),
            'password_change_date' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Your password has been successfully changed.',
        ]);
    }

    public function uploadAvatar(Request $request): JsonResponse
    {
        $teacher = auth('teacher')->user();
        if (! $teacher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.',
            ], 403);
        }

        $request->validate([
            'photoimg' => 'required|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('photoimg');
        $ext = $file->getClientOriginalExtension();
        $filename = Str::random(12).'.'.strtolower($ext);

        $path = $file->storeAs('teachers', $filename, 'public');
        if (! $path) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to save the image. Please try again.',
            ]);
        }

        $teacher->update(['imagelocation' => $filename]);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile picture updated.',
            'image_url' => asset('storage/teachers/'.$filename),
        ]);
    }
}
