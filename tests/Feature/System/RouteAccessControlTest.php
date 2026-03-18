<?php

declare(strict_types=1);

namespace Tests\Feature\System;

use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RouteAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Admin::query()->firstOrCreate(
            ['adminId' => 'route-admin'],
            [
                'name' => 'Route Admin',
                'email' => 'routeadmin@test.local',
                'password' => Hash::make('password'),
                'user_type' => 1,
            ]
        );
        Teacher::query()->firstOrCreate(
            ['userId' => 'route-teacher'],
            [
                'email' => 'route@teacher.local',
                'firstname' => 'Route',
                'lastname' => 'Teacher',
                'password' => Hash::make('password'),
            ]
        );
    }

    public function test_guest_cannot_access_admin_routes(): void
    {
        $this->get(route('admin.dashboard'))->assertUnauthorized();
        $this->get(route('admin.classes'))->assertUnauthorized();
        $this->get(route('admin.staff.index'))->assertUnauthorized();
    }

    public function test_guest_cannot_access_teacher_routes(): void
    {
        $this->get(route('teacher.dashboard'))->assertUnauthorized();
        $this->get(route('teacher.behavioral.index'))->assertUnauthorized();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = Admin::first();
        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_teacher_can_access_teacher_dashboard(): void
    {
        $teacher = Teacher::first();
        $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.dashboard'))
            ->assertOk();
    }
}
