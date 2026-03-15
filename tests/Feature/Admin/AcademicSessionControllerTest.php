<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\AcademicSession;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AcademicSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Role::query()->firstOrCreate(['id' => 1], ['name' => 'Admin', 'permissions' => null]);
        Setting::query()->firstOrCreate(['id' => 1], [
            'name' => 'School',
            'session' => '2024/2025',
            'term' => '1',
            'segment' => 'First',
        ]);
        $this->admin = Admin::query()->firstOrCreate(
            ['adminId' => 'session-admin'],
            [
                'name' => 'Session Admin',
                'email' => 'session@test.local',
                'password' => Hash::make('password'),
                'user_type' => 1,
                'joined' => now(),
            ]
        );
    }

    public function test_guest_cannot_access_sessions_index(): void
    {
        $response = $this->get(route('admin.sessions.index'));
        $response->assertRedirect();
        $this->assertTrue(str_contains($response->headers->get('Location') ?? '', 'login'));
    }

    public function test_admin_can_see_sessions_index(): void
    {
        AcademicSession::query()->create(['year' => '2024/2025']);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.sessions.index'));
        $response->assertOk();
        $response->assertSee('2024/2025');
    }

    public function test_admin_can_create_session(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.sessions.store'), [
            'year' => '2025/2026',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('admin.sessions.index'));
        $this->assertDatabaseHas('academic_sessions', ['year' => '2025/2026']);
    }

    public function test_admin_can_update_session(): void
    {
        $session = AcademicSession::query()->create(['year' => '2023/2024']);

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.sessions.update', $session->id), [
            'year' => '2023/2024 Revised',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('admin.sessions.index'));
        $this->assertDatabaseHas('academic_sessions', ['id' => $session->id, 'year' => '2023/2024 Revised']);
    }

    public function test_admin_can_activate_session(): void
    {
        $session = AcademicSession::query()->create(['year' => '2025/2026']);
        $this->assertNotSame('2025/2026', Setting::getCached()['session'] ?? null);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.sessions.activate', $session->id), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('admin.sessions.index'));
        $this->assertSame('2025/2026', Setting::getCached()['session']);
    }

    public function test_admin_can_delete_session(): void
    {
        $session = AcademicSession::query()->create(['year' => '2022/2023']);

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.sessions.destroy', $session->id));

        $response->assertRedirect(route('admin.sessions.index'));
        $this->assertDatabaseMissing('academic_sessions', ['id' => $session->id]);
    }
}
