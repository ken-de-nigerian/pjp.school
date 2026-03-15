<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Admin::query()->firstOrCreate(
            ['adminId' => 'settings-admin'],
            [
                'name' => 'Settings Admin',
                'email' => 'settings@test.local',
                'password' => Hash::make('password'),
                'user_type' => 1,
            ]
        );
    }

    public function test_guest_cannot_access_settings(): void
    {
        $response = $this->get(route('admin.settings.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_settings_index(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.settings.index');
    }

    public function test_admin_can_update_settings(): void
    {
        Setting::query()->firstOrCreate(
            ['id' => 1],
            ['name' => 'School', 'term' => '1', 'session' => '2024/2025']
        );
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.settings.update'), [
            'name' => 'Updated School',
            'term' => '1',
            'session' => '2024/2025',
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('settings', ['name' => 'Updated School']);
    }
}
