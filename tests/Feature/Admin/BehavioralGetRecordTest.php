<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Behavioral;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BehavioralGetRecordTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Role::query()->firstOrCreate(['id' => 1], ['name' => 'Admin', 'permissions' => null]);
        $this->admin = Admin::query()->firstOrCreate(
            ['adminId' => 'beh-get-admin'],
            [
                'name' => 'Beh Get',
                'email' => 'behget@test.local',
                'password' => Hash::make('password'),
                'user_type' => 1,
                'joined' => now(),
            ]
        );
    }

    public function test_guest_cannot_get_behavioral_record(): void
    {
        $response = $this->getJson(route('admin.behavioral.record', [
            'class' => 'SS1',
            'term' => '1',
            'session' => '2024/2025',
            'segment' => 'First',
        ]));

        $response->assertStatus(401);
    }

    public function test_admin_can_get_behavioral_record_returns_json_array(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->getJson(route('admin.behavioral.record', [
                'class' => 'SS1',
                'term' => '1',
                'session' => '2024/2025',
                'segment' => 'First',
            ]));

        $response->assertOk();
        $response->assertJsonIsArray();
    }

    public function test_admin_can_save_behavioral_bulk(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->postJson(route('admin.behavioral.save'), [
            'students' => [
                [
                    'class' => 'JSS 1',
                    'term' => '1',
                    'session' => '2024/2025',
                    'segment' => 'First',
                    'name' => 'Alice',
                    'reg_number' => '1001',
                    'neatness' => 'A',
                    'music' => 'B',
                    'sports' => 'A',
                    'attentiveness' => 'B',
                    'punctuality' => 'A',
                    'health' => 'B',
                    'politeness' => 'A',
                ],
            ],
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');
        $this->assertDatabaseHas('behavioral', ['reg_number' => '1001', 'class' => 'JSS 1']);
    }

    public function test_save_rejects_when_already_exists(): void
    {
        Behavioral::query()->create([
            'class' => 'JSS 1', 'term' => '1', 'session' => '2024/2025', 'segment' => 'First',
            'name' => 'X', 'reg_number' => '1001', 'neatness' => '1', 'music' => '1', 'sports' => '1',
            'attentiveness' => '1', 'punctuality' => '1', 'health' => '1', 'politeness' => '1', 'date_added' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')->postJson(route('admin.behavioral.save'), [
            'students' => [
                [
                    'class' => 'JSS 1',
                    'term' => '1',
                    'session' => '2024/2025',
                    'segment' => 'First',
                    'name' => 'Alice',
                    'reg_number' => '1002',
                    'neatness' => 'A', 'music' => 'A', 'sports' => 'A', 'attentiveness' => 'A',
                    'punctuality' => 'A', 'health' => 'A', 'politeness' => 'A',
                ],
            ],
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'error');
        $response->assertJsonFragment(['message' => 'Behavioral analysis for JSS 1 in term 1 and session 2024/2025 already exists']);
    }

    public function test_admin_can_edit_behavioral_record(): void
    {
        Behavioral::query()->create([
            'class' => 'JSS 1', 'term' => '1', 'session' => '2024/2025', 'segment' => 'First',
            'name' => 'Alice', 'reg_number' => '1001', 'neatness' => 'A', 'music' => 'A', 'sports' => 'A',
            'attentiveness' => 'A', 'punctuality' => 'A', 'health' => 'A', 'politeness' => 'A', 'date_added' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')->putJson(route('admin.behavioral.edit'), [
            'reg_number' => '1001',
            'class' => 'JSS 1',
            'term' => '1',
            'session' => '2024/2025',
            'segment' => 'First',
            'neatness' => 'B',
            'music' => 'B',
            'sports' => 'B',
            'attentiveness' => 'B',
            'punctuality' => 'B',
            'health' => 'B',
            'politeness' => 'B',
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');
        $this->assertDatabaseHas('behavioral', ['reg_number' => '1001', 'neatness' => 'B']);
    }
}
