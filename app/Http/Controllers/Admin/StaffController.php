<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetStaffPasswordRequest;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Http\Requests\UploadStaffProfileRequest;
use App\Models\Admin;
use App\Services\StaffService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Random\RandomException;

class StaffController extends Controller
{
    public function __construct(
        private readonly StaffService $staffService
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Admin::class);

        $perPage = config('school.pagination.staff', 15);
        $roles = $this->staffService->getAllRoles();
        $searchQuery = $request->input('search', '');

        if ($searchQuery !== '') {
            $staff = null;
            $searchResults = $this->staffService->search($request);
        } else {
            $staff = $this->staffService->list($perPage);
            $searchResults = null;
        }

        return view('admin.staff.index', [
            'staff' => $staff,
            'roles' => $roles,
            'searchResults' => $searchResults,
            'searchQuery' => $searchQuery,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Admin::class);

        $roles = $this->staffService->getAllRoles();

        return view('admin.staff.create', ['roles' => $roles]);
    }

    /**
     * @return RedirectResponse|JsonResponse
     * @throws RandomException
     */
    public function store(StoreStaffRequest $request)
    {
        Gate::authorize('create', Admin::class);

        if ($this->staffService->hasAdminEmail($request->input('email'))) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['email' => [__('This email is registered to another staff.')]],
                ], 422);
            }
            return back()->withErrors(['email' => __('This email is registered to another staff.')])->withInput();
        }

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $this->staffService->create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Staff account has been registered successfully.'),
                'redirect' => route('admin.staff.index'),
            ]);
        }

        return redirect()
            ->route('admin.staff.index')
            ->with('success', __('Staff account has been registered successfully.'));
    }

    public function edit(Admin $admin): View
    {
        Gate::authorize('update', $admin);

        $roles = $this->staffService->getAllRoles();

        return view('admin.staff.edit', [
            'staff' => $admin,
            'roles' => $roles,
        ]);
    }

    /**
     * @return RedirectResponse|JsonResponse
     */
    public function update(UpdateStaffRequest $request, Admin $admin)
    {
        Gate::authorize('update', $admin);

        if ($this->staffService->hasAdminEmail($request->input('email'), $admin->adminId)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['email' => [__('This email is registered to another staff.')]],
                ], 422);
            }
            return back()->withErrors(['email' => __('This email is registered to another staff.')])->withInput();
        }

        $updated = $this->staffService->update($admin->adminId, $request->validated());

        if ($request->wantsJson()) {
            if ($updated === 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => __('No changes have been made to this staff\'s account.'),
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => __('Staff account has been updated successfully.'),
            ]);
        }

        if ($updated === 0) {
            return back()->with('info', __('No changes have been made to this staff\'s account.'));
        }

        return redirect()
            ->route('admin.staff.edit', $admin->adminId)
            ->with('success', __('Staff account has been updated successfully.'));
    }

    public function uploadProfile(UploadStaffProfileRequest $request, Admin $admin): JsonResponse
    {
        Gate::authorize('update', $admin);

        $file = $request->file('photoimg');
        $ext = $file->getClientOriginalExtension();
        $filename = Str::random(12) . '.' . strtolower($ext);

        $path = $file->storeAs('staffs', $filename, 'public');
        if (! $path) {
            return response()->json([
                'status' => 'error',
                'message' => __('Unable to save the image. Please try again.'),
            ], 500);
        }

        $admin->update(['profileImage' => $filename]);

        return response()->json([
            'status' => 'success',
            'message' => __('Profile picture updated.'),
        ]);
    }

    /**
     * @return RedirectResponse|JsonResponse
     */
    public function destroy(Admin $admin)
    {
        Gate::authorize('delete', $admin);

        if ($admin->adminId === request()->user('admin')->adminId) {
            if (request()->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __("You can't delete your own account."),
                ], 403);
            }
            return redirect()
                ->route('admin.staff.index')
                ->with('error', __("You can't delete your own account."));
        }

        $this->staffService->delete($admin->adminId);

        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Staff deleted.'),
                'redirect' => route('admin.staff.index'),
            ]);
        }

        return redirect()
            ->route('admin.staff.index')
            ->with('success', __('Staff deleted.'));
    }

    public function resetPassword(ResetStaffPasswordRequest $request, Admin $admin): RedirectResponse
    {
        Gate::authorize('update', $admin);

        $this->staffService->resetPassword($admin->adminId, Hash::make($request->input('password')));

        return redirect()
            ->route('admin.staff.edit', $admin->adminId)
            ->with('success', __('Staff\'s password has been successfully changed.'));
    }
}
