<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TeacherLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedTeacher();
    }

    private function seedTeacher(): void
    {
        Teacher::query()->firstOrCreate(
            ['userId' => 'teacher-1'],
            [
                'email' => 'teacher@test.local',
                'firstname' => 'Test',
                'lastname' => 'Teacher',
                'password' => Hash::make('password'),
            ]
        );
    }

    public function test_login_page_renders(): void
    {
        $response = $this->get(route('teacher.login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.teacher.login');
    }

    public function test_valid_credentials_redirect_to_teacher_dashboard(): void
    {
        $response = $this->post(route('teacher.login'), [
            'email' => 'teacher@test.local',
            'password' => 'password',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('teacher.dashboard'));
        $this->assertAuthenticatedAs(Teacher::first(), 'teacher');
    }
}
