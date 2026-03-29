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
            ['email' => 'logout@test.local'],
            [
                'name' => 'Logout Admin',
                'password' => Hash::make('password'),
                'user_type' => 1,
            ]
        );
    }

    public function test_authenticated_admin_can_logout(): void
    {
        $admin = Admin::query()->firstOrFail();

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.logout'), ['_token' => csrf_token()]);

        $response->assertRedirect();
        $this->assertGuest('admin');
    }
}
