<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AttendanceGetRecordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Admin::query()->firstOrCreate(
            ['email' => 'attget@test.local'],
            [
                'name' => 'Att Get',
                'password' => Hash::make('password'),
                'user_type' => 1,
            ]
        );
    }

    public function test_guest_cannot_get_attendance_record(): void
    {
        $response = $this->getJson(route('admin.attendance.record', [
            'date' => '01 Jan 2025',
            'class' => 'SS1',
            'term' => '1',
            'session' => '2024/2025',
            'segment' => 'AM',
        ]));

        $response->assertStatus(401);
    }

    public function test_admin_can_get_attendance_record_returns_json_array(): void
    {
        $admin = Admin::query()->firstOrFail();

        $response = $this->actingAs($admin, 'admin')
            ->getJson(route('admin.attendance.record', [
                'date' => '01 Jan 2025',
                'class' => 'SS1',
                'term' => '1',
                'session' => '2024/2025',
                'segment' => 'AM',
            ]));

        $response->assertOk();
        $response->assertJsonIsArray();
    }
}
