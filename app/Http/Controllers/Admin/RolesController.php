<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Notification;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RolesController extends Controller
{
    private const PERMISSION_DEFAULTS = [
        'attendance' => 0,
        'view_uploaded_attendance' => 0,
        'behavioural_analysis' => 0,
        'view_uploaded_behavioural_analysis' => 0,
        'manage_subjects' => 0,
        'upload_result' => 0,
        'view_uploaded_results' => 0,
        'publish_result' => 0,
        'view_published_results' => 0,
        'transcript' => 0,
        'check_result_status' => 0,
        'manage_students' => 0,
        'manage_teachers' => 0,
        'manage_staffs' => 0,
        'online_entrance' => 0,
        'manage_scratch_card' => 0,
        'news' => 0,
        'bulk_sms' => 0,
        'general_settings' => 0,
    ];

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Role::class);

        $perPage = $request->integer('per_page', 8);
        $roles = Role::query()
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.roles.index', [
            'roles' => $roles,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Role::class);

        return view('admin.roles.create', [
            'permissionKeys' => Role::permissionKeys(),
        ]);
    }

    public function store(StoreRoleRequest $request): JsonResponse|RedirectResponse
    {
        Gate::authorize('create', Role::class);

        $data = array_merge(self::PERMISSION_DEFAULTS, $request->validated());
        $name = $data['name'] ?? '';
        unset($data['name']);
        $data['name'] = $name;

        $role = Role::query()->create($data);
        $enabledCount = count($role->fresh()->enabledPermissionLabels());

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Roles And Permissions Added',
                'message' => $admin->name . ' has added a new role & permissions: ' . $name,
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Role and Permission has been added successfully.'),
                'role' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'enabled_count' => $enabledCount,
                    'total_permissions' => count(Role::permissionKeys()),
                ],
            ]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', __('Role and Permission has been added successfully.'));
    }

    public function edit(int $id): View|RedirectResponse
    {
        $role = Role::query()->find($id);
        if (! $role) {
            abort(404);
        }
        Gate::authorize('update', $role);

        return view('admin.roles.edit', [
            'role' => $role,
            'permissionKeys' => Role::permissionKeys(),
        ]);
    }

    public function update(UpdateRoleRequest $request, int $id): JsonResponse|RedirectResponse
    {
        $role = Role::query()->find($id);
        if (! $role) {
            abort(404);
        }
        Gate::authorize('update', $role);

        $data = array_merge(self::PERMISSION_DEFAULTS, $request->validated());
        $name = $data['name'] ?? $role->name;
        unset($data['name']);
        $data['name'] = $name;

        $role->update($data);
        $role->refresh();
        $enabledCount = count($role->enabledPermissionLabels());

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Roles And Permissions Edited',
                'message' => $admin->name . ' has edited some roles & permissions',
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Roles and Permissions has been edited successfully.'),
                'role' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'enabled_count' => $enabledCount,
                    'total_permissions' => count(Role::permissionKeys()),
                ],
            ]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', __('Roles and Permissions has been edited successfully.'));
    }

    public function destroy(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $role = Role::query()->find($id);
        if (! $role) {
            abort(404);
        }
        Gate::authorize('delete', $role);

        $role->delete();

        $admin = $request->user('admin');
        if ($admin) {
            Notification::query()->create([
                'title' => 'Role Deleted',
                'message' => $admin->name . ' has deleted a role with ID: ' . $role->name,
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'This role has been deleted successfully.',
                'redirect' => route('admin.roles.index'),
            ]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', __('This role has been deleted successfully.'));
    }
}
