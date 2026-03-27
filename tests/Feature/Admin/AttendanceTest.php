<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Admin::query()->firstOrCreate(
            ['adminId' => 'att-admin'],
            [
                'name' => 'Att Admin',
                'email' => 'att@test.local',
                'password' => Hash::make('password'),
                'user_type' => 1,
            ]
        );
    }

    public function test_guest_cannot_access_attendance_save(): void
    {
        $response = $this->post(route('admin.attendance.save'), [
            'attendance' => [],
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_attendance_save_validates_attendance_array(): void
    {
        $admin = Admin::query()->firstOrFail();

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.attendance.save'), [
                'attendance' => [['class' => 'SS1', 'term' => '1', 'session' => '2024', 'segment' => 'AM', 'name' => 'A', 'reg_number' => 'R1', 'class_roll_call' => 'present']],
                '_token' => csrf_token(),
            ]);

        $response->assertOk();
        $response->assertJson(['status' => 'success']);
        $response->assertSessionHasNoErrors();
    }

    public function test_admin_can_edit_attendance_record(): void
    {
        $admin = Admin::query()->firstOrFail();
        \App\Models\AttendanceRecord::query()->insert([
            'class' => 'SS1',
            'term' => '1',
            'session' => '2024',
            'segment' => config('school.no_segment', 'No Segment'),
            'name' => 'Alice',
            'reg_number' => 'R001',
            'class_roll_call' => 1,
            'date_added' => now()->format('Y-m-d H:i:s'),
        ]);

        $dateStr = now()->format('Y-m-d');
        $response = $this->actingAs($admin, 'admin')->putJson(route('admin.attendance.edit'), [
            'class' => 'SS1',
            'term' => '1',
            'session' => '2024',
            'date' => $dateStr,
            'updates' => [
                ['reg_number' => 'R001', 'class_roll_call' => 'Absent'],
            ],
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');
        $this->assertDatabaseHas('attendance_list', ['reg_number' => 'R001', 'class_roll_call' => 2]);
    }

    public function test_admin_can_delete_attendance_record(): void
    {
        $admin = Admin::query()->firstOrFail();
        \App\Models\AttendanceRecord::query()->insert([
            'class' => 'JSS1',
            'term' => '1',
            'session' => '2024',
            'segment' => config('school.no_segment', 'No Segment'),
            'name' => 'Bob',
            'reg_number' => 'R002',
            'class_roll_call' => 1,
            'date_added' => now()->format('Y-m-d H:i:s'),
        ]);

        $response = $this->actingAs($admin, 'admin')->postJson(route('admin.attendance.destroy'), [
            'class' => 'JSS1',
            'term' => '1',
            'session' => '2024',
            'date' => now()->format('Y-m-d'),
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');
        $this->assertDatabaseMissing('attendance_list', ['reg_number' => 'R002', 'class' => 'JSS1']);
    }
}
