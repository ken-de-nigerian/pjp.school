<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Admin;
use App\Models\Role;
use App\Services\StaffService;
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
            'adminId' => 'first',
            'name' => 'First',
            'email' => 'first@test.local',
            'password' => Hash::make('p'),
            'user_type' => 1,
            'joined' => now()->subDay(),
        ]);
        Admin::query()->create([
            'adminId' => 'second',
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

    public function test_count_all(): void
    {
        Role::query()->create(['id' => 1, 'name' => 'R', 'permissions' => null]);
        Admin::query()->create([
            'adminId' => 'a1',
            'name' => 'A',
            'email' => 'a@t.local',
            'password' => Hash::make('p'),
            'user_type' => 1,
        ]);

        $this->assertSame(1, $this->service->countAll());
    }

    public function test_get_staff_returns_admin(): void
    {
        Role::query()->firstOrCreate(['id' => 1], ['name' => 'R', 'permissions' => null]);
        $admin = Admin::query()->create([
            'adminId' => 'sid1',
            'name' => 'Staff',
            'email' => 's@t.local',
            'password' => Hash::make('p'),
            'user_type' => 1,
            'joined' => now(),
        ]);

        $found = $this->service->getStaff('sid1');
        $this->assertNotNull($found, 'getStaff(sid1) should return the admin');
        $this->assertSame('Staff', $found->name);
        $this->assertSame('s@t.local', $found->email);
        $this->assertNull($this->service->getStaff('nonexistent'));
    }

    public function test_has_admin_email(): void
    {
        Role::query()->create(['id' => 1, 'name' => 'R', 'permissions' => null]);
        Admin::query()->create([
            'adminId' => 'a1',
            'name' => 'A',
            'email' => 'exists@test.local',
            'password' => Hash::make('p'),
            'user_type' => 1,
        ]);

        $this->assertTrue($this->service->hasAdminEmail('exists@test.local'));
        $this->assertFalse($this->service->hasAdminEmail('other@test.local'));
        $this->assertFalse($this->service->hasAdminEmail('exists@test.local', 'a1'));
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
        Admin::query()->create([
            'adminId' => 'u1',
            'name' => 'Old',
            'email' => 'old@test.local',
            'password' => Hash::make('p'),
            'user_type' => 1,
        ]);

        $count = $this->service->update('u1', [
            'name' => 'New Name',
            'email' => 'old@test.local',
            'phone' => null,
            'user_type' => 2,
        ]);

        $this->assertSame(1, $count);
        $admin = Admin::query()->where('adminId', 'u1')->first();
        $this->assertSame('New Name', $admin->name);
        $this->assertSame(2, (int) $admin->user_type);
    }

    public function test_search_finds_by_name_or_email(): void
    {
        Role::query()->create(['id' => 1, 'name' => 'R', 'permissions' => null]);
        Admin::query()->create([
            'adminId' => 's1',
            'name' => 'John Doe',
            'email' => 'john@test.local',
            'password' => Hash::make('p'),
            'user_type' => 1,
        ]);

        $results = $this->service->search('John');
        $this->assertCount(1, $results);
        $this->assertSame('John Doe', $results->first()->name);

        $results2 = $this->service->search('john@test');
        $this->assertCount(1, $results2);
    }

    public function test_delete_removes_staff(): void
    {
        Role::query()->create(['id' => 1, 'name' => 'R', 'permissions' => null]);
        Admin::query()->create([
            'adminId' => 'd1',
            'name' => 'Del',
            'email' => 'del@test.local',
            'password' => Hash::make('p'),
            'user_type' => 1,
        ]);

        $this->service->delete('d1');

        $this->assertDatabaseMissing('admin', ['adminId' => 'd1']);
    }
}
