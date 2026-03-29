<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StaffControllerTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    private Role $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->role = Role::query()->firstOrCreate(
            ['id' => 1],
            ['name' => 'Super Admin', 'permissions' => null]
        );
        $this->admin = Admin::query()->firstOrCreate(
            ['email' => 'staffadmin@test.local'],
            [
                'name' => 'Staff Admin',
                'password' => Hash::make('password'),
                'user_type' => $this->role->id,
                'joined' => now(),
            ]
        );
    }

    public function test_guest_cannot_access_staff(): void
    {
        $response = $this->get(route('admin.staff.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_staff_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.staff.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.staff.index');
        $response->assertViewHas('roles');
    }

    public function test_admin_can_access_create_form(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.staff.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.staff.create');
        $response->assertViewHas('roles');
    }

    public function test_admin_can_store_staff(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.staff.store'), [
            'name' => 'New Staff',
            'email' => 'newstaff@test.local',
            'phone' => '123',
            'password' => 'password123',
            'user_type' => $this->role->id,
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $this->assertDatabaseHas('admin', [
            'name' => 'New Staff',
            'email' => 'newstaff@test.local',
            'user_type' => $this->role->id,
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.staff.store'), [
            'name' => '',
            'email' => '',
            'password' => 'short',
            'user_type' => '',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'user_type']);
    }

    public function test_store_rejects_duplicate_email(): void
    {
        $this->actingAs($this->admin, 'admin')->post(route('admin.staff.store'), [
            'name' => 'New Staff',
            'email' => 'dup@test.local',
            'password' => 'password123',
            'user_type' => $this->role->id,
            '_token' => csrf_token(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.staff.store'), [
            'name' => 'Other',
            'email' => 'dup@test.local',
            'password' => 'password123',
            'user_type' => $this->role->id,
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_admin_can_view_staff_show(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.staff.edit', $this->admin));

        $response->assertStatus(200);
        $response->assertViewIs('admin.staff.edit');
        $response->assertViewHas('staff', $this->admin);
    }

    public function test_show_returns_404_for_missing_staff(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.staff.edit', 999_999));

        $response->assertStatus(404);
    }

    public function test_admin_can_update_staff_and_role(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.staff.update', $this->admin), [
            'name' => 'Updated Name',
            'email' => $this->admin->email,
            'phone' => '999',
            'user_type' => $this->role->id,
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('admin.staff.edit', $this->admin));
        $this->admin->refresh();
        $this->assertSame('Updated Name', $this->admin->name);
        $this->assertSame('999', $this->admin->phone);
    }

    public function test_admin_can_reset_staff_password(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.staff.reset-password', $this->admin), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('admin.staff.edit', $this->admin));
    }

    public function test_super_admin_can_delete_other_staff(): void
    {
        $other = Admin::query()->create([
            'name' => 'Other',
            'email' => 'other@test.local',
            'password' => Hash::make('p'),
            'user_type' => $this->role->id,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.staff.destroy', $other));

        $response->assertRedirect(route('admin.staff.index'));
        $this->assertDatabaseMissing('admin', ['email' => 'other@test.local']);
    }

    public function test_search_returns_staff_by_name_or_email(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.staff.index', ['search' => 'Staff Admin']));

        $response->assertStatus(200);
        $response->assertViewHas('searchResults');
        $searchResults = $response->viewData('searchResults');
        $this->assertInstanceOf(Collection::class, $searchResults);
        $this->assertGreaterThanOrEqual(1, $searchResults->count());
    }
}
