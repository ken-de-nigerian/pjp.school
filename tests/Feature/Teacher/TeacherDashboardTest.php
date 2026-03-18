<?php

declare(strict_types=1);

namespace Tests\Feature\Teacher;

use App\Models\News;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Feature test: Teacher dashboard data matches legacy (user, news 3 per page).
 */
class TeacherDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Teacher::query()->firstOrCreate(
            ['userId' => 'teacher-dash-1'],
            [
                'email' => 'teacherdash@test.local',
                'firstname' => 'Teach',
                'lastname' => 'Er',
                'password' => Hash::make('password'),
            ]
        );
    }

    public function test_teacher_dashboard_renders_with_user(): void
    {
        $teacher = Teacher::first();
        $response = $this->actingAs($teacher, 'teacher')->get(route('teacher.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('user');
        $response->assertViewHas('get_news');
        $response->assertViewHas('news');
    }

    public function test_teacher_dashboard_shows_news_paginated(): void
    {
        News::query()->create([
            'newsid' => (string) Str::uuid(),
            'title' => 'News 1',
            'content' => 'C1',
            'slug' => 'news-1',
            'category' => 'General',
            'author' => 'Admin',
            'imagelocation' => 'default.png',
        ]);
        $teacher = Teacher::first();

        $response = $this->actingAs($teacher, 'teacher')->get(route('teacher.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('get_news');
        $this->assertLessThanOrEqual(3, count($response->viewData('get_news')));
    }
}
