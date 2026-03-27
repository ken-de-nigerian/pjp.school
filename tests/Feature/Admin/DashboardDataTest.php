<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\News;
use App\Models\Role;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Feature test: Admin dashboard data matches legacy (counts, news pagination, role).
 */
class DashboardDataTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $role = Role::query()->firstOrCreate(
            ['id' => 1],
            ['name' => 'Super Admin']
        );
        Admin::query()->firstOrCreate(
            ['adminId' => 'dash-data-admin'],
            [
                'name' => 'Dash Data',
                'email' => 'dashdata@test.local',
                'password' => Hash::make('password'),
                'user_type' => $role->id,
            ]
        );
    }

    public function test_dashboard_returns_all_legacy_counts(): void
    {
        Student::query()->create([
            'reg_number' => 'R1',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'SS1',
            'status' => 2,
            'category' => 'Boarding',
        ]);
        Student::query()->create([
            'reg_number' => 'R2',
            'firstname' => 'C',
            'lastname' => 'D',
            'class' => 'SS1',
            'status' => 2,
            'category' => 'Day',
        ]);
        Subject::query()->create(['subject_name' => 'Math', 'grade' => 'Senior']);
        Teacher::query()->create([
            'userId' => 't1',
            'email' => 't@t.local',
            'firstname' => 'T',
            'lastname' => 'One',
            'password' => Hash::make('p'),
        ]);

        $admin = Admin::query()->firstOrFail();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('count_all_students', 2);
        $response->assertViewHas('count_boarding_students', 1);
        $response->assertViewHas('count_day_students', 1);
        $response->assertViewHas('count_subjects', 1);
        $response->assertViewHas('count_teachers', 1);
    }

    public function test_dashboard_includes_paginated_news(): void
    {
        News::query()->create([
            'newsid' => (string) Str::uuid(),
            'title' => 'Test News',
            'content' => 'Body',
            'slug' => 'test-news',
            'category' => 'General',
            'author' => 'Admin',
            'imagelocation' => 'default.png',
        ]);
        $admin = Admin::query()->firstOrFail();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('get_news');
        $response->assertViewHas('currentPage');
        $response->assertViewHas('totalItems');
        $response->assertViewHas('totalPages');
    }
}
