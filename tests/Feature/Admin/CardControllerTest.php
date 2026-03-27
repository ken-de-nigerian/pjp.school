<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Role;
use App\Models\UnusedPin;
use App\Models\UsedPin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CardControllerTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Role::query()->firstOrCreate(['id' => 1], ['name' => 'Admin', 'permissions' => null]);
        \App\Models\Setting::query()->firstOrCreate(['id' => 1], [
            'name' => 'School',
            'session' => '2024/2025',
            'term' => '1',
        ]);
        $this->admin = Admin::query()->firstOrCreate(
            ['adminId' => 'card-admin'],
            [
                'name' => 'Card Admin',
                'email' => 'card@test.local',
                'password' => Hash::make('password'),
                'user_type' => 1,
                'joined' => now(),
            ]
        );
    }

    public function test_guest_cannot_access_card_index(): void
    {
        $response = $this->get(route('admin.card.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_see_card_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.card.index'));
        $response->assertOk();
        $response->assertViewIs('admin.card.index');
        $response->assertSee('Scratch Card');
    }

    public function test_admin_can_see_unused_pins(): void
    {
        UnusedPin::query()->create([
            'session' => '2024/2025',
            'pins' => 'PIN001',
            'serial_number' => '123',
            'upload_date' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.card.unused-pins'));
        $response->assertOk();
        $response->assertSee('PIN001');
    }

    public function test_admin_can_see_used_pins(): void
    {
        UsedPin::query()->create([
            'pins' => 'PIN002',
            'reg_number' => 'R1',
            'used_count' => 1,
            'class' => 'SS1',
            'term' => '1',
            'session' => '2024/2025',
            'time_used' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.card.used-pins'));
        $response->assertOk();
        $response->assertSee('PIN002');
    }

    public function test_admin_can_generate_pins(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->postJson(route('admin.card.generate-pins.store'), [
            'session' => '2024/2025',
            'count' => 2,
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');
        $this->assertDatabaseCount('unused_pins', 2);
    }

    public function test_generate_rejects_invalid_count(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->postJson(route('admin.card.generate-pins.store'), [
            'session' => '2024/2025',
            'count' => 0,
            '_token' => csrf_token(),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['count']);
    }
}
