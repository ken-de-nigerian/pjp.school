<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedAdmin();
    }

    private function seedAdmin(): void
    {
        Admin::query()->firstOrCreate(
            ['adminId' => 'test-admin-1'],
            [
                'name' => 'Test Admin',
                'email' => 'admin@test.local',
                'password' => Hash::make('password'),
                'user_type' => 1,
            ]
        );
    }

    public function test_login_page_renders(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.admin.login');
        $response->assertSee('Login', false);
    }

    public function test_invalid_credentials_return_validation_error(): void
    {
        $response = $this->post(route('admin.login'), [
            'email' => 'admin@test.local',
            'password' => 'wrong',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_valid_credentials_redirect_to_dashboard(): void
    {
        $response = $this->post(route('admin.login'), [
            'email' => 'admin@test.local',
            'password' => 'password',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs(Admin::first(), 'admin');
    }
}
