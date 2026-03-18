<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeTeacherPasswordRequest;
use App\Http\Requests\UpdateTeacherProfileRequest;
use App\Http\Requests\UploadTeacherAvatarRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * View teacher profile. Legacy: GET teacher/profile.
     */
    public function index(): View
    {
        $user = request()->user('teacher');

        return view('teacher.profile.index', [
            'user' => $user,
        ]);
    }

    /**
     * Update teacher profile. Legacy: POST teacher/profile.
     * Returns JSON { status, message }.
     */
    public function update(UpdateTeacherProfileRequest $request): JsonResponse
    {
        $user = $request->user('teacher');
        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.',
            ], 403);
        }

        $user->update([
            'firstname' => $request->validated('firstname'),
            'lastname' => $request->validated('lastname'),
            'phone' => $request->validated('formattedPhone'),
            'address' => $request->validated('address'),
            'country' => $request->validated('country'),
            'gender' => $request->validated('gender'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Your profile information has been updated successfully.',
        ]);
    }

    /**
     * Change teacher password. Legacy: POST teacher/password.
     * Returns JSON { status, message }.
     */
    public function changePassword(ChangeTeacherPasswordRequest $request): JsonResponse
    {
        $user = $request->user('teacher');
        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.',
            ], 403);
        }

        if (! Hash::check($request->input('oldPassword'), $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your current password does not match our records. Please try again.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Your password has been successfully changed.',
        ]);
    }

    /**
     * Upload teacher profile picture. Legacy: GET teacher/upload (form), POST teacher/upload (AJAX photoimg).
     * Returns JSON { status } or { status, message }.
     */
    public function upload(UploadTeacherAvatarRequest $request): JsonResponse
    {
        $user = $request->user('teacher');
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 403);
        }

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

        $user->update(['profileImage' => $filename]);

        return response()->json(['status' => 'success']);
    }

    public function uploadPage()
    {
        //
    }
}
