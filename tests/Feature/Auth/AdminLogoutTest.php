<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLogoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Admin::query()->firstOrCreate(
            ['adminId' => 'logout-admin'],
            [
                'name' => 'Logout Admin',
                'email' => 'logout@test.local',
                'password' => Hash::make('password'),
                'user_type' => 1,
            ]
        );
    }

    public function test_authenticated_admin_can_logout(): void
    {
        $admin = Admin::first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.logout'), ['_token' => csrf_token()]);

        $response->assertRedirect();
        $this->assertGuest('admin');
    }
}
