<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Admin;
use App\Models\Role;
use App\Services\StaffService;
use App\Support\Coercion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StaffServiceTest extends TestCase
{
    use RefreshDatabase;

    private StaffService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StaffService;
    }

    public function test_list_orders_by_joined(): void
    {
        Role::query()->create(['id' => 1, 'name' => 'Admin', 'permissions' => null]);
        Admin::query()->create([
            'name' => 'First',
            'email' => 'first@test.local',
            'password' => Hash::make('p'),
            'user_type' => 1,
            'joined' => now()->subDay(),
        ]);
        Admin::query()->create([
            'name' => 'Second',
            'email' => 'second@test.local',
            'password' => Hash::make('p'),
            'user_type' => 1,
            'joined' => now(),
        ]);

        $paginator = $this->service->list(10);

        $this->assertSame(2, $paginator->total());
        $this->assertSame('First', $paginator->items()[0]->name);
    }

    public function test_has_admin_email(): void
    {
        Role::query()->create(['id' => 1, 'name' => 'R', 'permissions' => null]);
        $existing = Admin::query()->create([
            'name' => 'A',
            'email' => 'exists@test.local',
            'password' => Hash::make('p'),
            'user_type' => 1,
        ]);

        $this->assertTrue($this->service->hasAdminEmail('exists@test.local'));
        $this->assertFalse($this->service->hasAdminEmail('other@test.local'));
        $this->assertFalse($this->service->hasAdminEmail('exists@test.local', $existing->id));
    }

    public function test_create_sets_profile_and_joined(): void
    {
        Role::query()->create(['id' => 1, 'name' => 'R', 'permissions' => null]);

        $admin = $this->service->create([
            'name' => 'New',
            'email' => 'new@test.local',
            'phone' => '123',
            'password' => Hash::make('secret'),
            'user_type' => 1,
        ]);

        $this->assertSame('New', $admin->name);
        $this->assertSame('default.png', $admin->profileImage);
        $this->assertNotNull($admin->joined);
    }

    public function test_update_changes_name_and_user_type(): void
    {
        Role::query()->create(['id' => 1, 'name' => 'R1', 'permissions' => null]);
        Role::query()->create(['id' => 2, 'name' => 'R2', 'permissions' => null]);
        $row = Admin::query()->create([
            'name' => 'Old',
            'email' => 'old@test.local',
            'password' => Hash::make('p'),
            'user_type' => 1,
        ]);

        $count = $this->service->update($row->id, [
            'name' => 'New Name',
            'email' => 'old@test.local',
            'phone' => null,
            'user_type' => 2,
        ]);

        $this->assertSame(1, $count);
        $admin = Admin::query()->whereKey($row->id)->firstOrFail();
        $this->assertSame('New Name', $admin->name);
        $this->assertSame(2, Coercion::int($admin->user_type));
    }

    public function test_delete_removes_staff(): void
    {
        Role::query()->create(['id' => 1, 'name' => 'R', 'permissions' => null]);
        $row = Admin::query()->create([
            'name' => 'Del',
            'email' => 'del@test.local',
            'password' => Hash::make('p'),
            'user_type' => 1,
        ]);

        $this->service->delete($row->id);

        $this->assertDatabaseMissing('admin', ['email' => 'del@test.local']);
    }
}
