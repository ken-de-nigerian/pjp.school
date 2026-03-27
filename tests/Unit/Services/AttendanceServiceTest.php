<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\AttendanceRecord;
use App\Services\AttendanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class AttendanceServiceTest extends TestCase
{
    use RefreshDatabase;

    private AttendanceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AttendanceService;
    }

    public function test_get_record_returns_empty_collection_for_invalid_date(): void
    {
        $result = $this->service->getRecord('invalid', 'SS1', '1', '2024');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);
    }

    public function test_get_record_returns_empty_collection_for_valid_date_with_no_data(): void
    {
        $result = $this->service->getRecord('01 Jan 2025', 'SS1', '1', '2024/2025');

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_save_record_returns_count_of_inserted_rows(): void
    {
        $attendance = [
            [
                'class' => 'SS1',
                'term' => '1',
                'session' => '2024',
                'segment' => 'AM',
                'name' => 'Student A',
                'reg_number' => 'R001',
                'class_roll_call' => 'present',
            ],
        ];

        $count = $this->service->saveRecord($attendance);

        $this->assertSame(1, $count);
    }

    public function test_edit_record_updates_class_roll_call(): void
    {
        $dateAdded = '2025-01-01 10:00:00';
        AttendanceRecord::query()->insert([
            'class' => 'SS1',
            'term' => '1',
            'session' => '2024/2025',
            'segment' => 'AM',
            'name' => 'Alice',
            'reg_number' => 'R001',
            'class_roll_call' => 'present',
            'date_added' => $dateAdded,
        ]);

        $count = $this->service->editRecord('SS1', '1', '2024/2025', '2025-01-01', [
            ['reg_number' => 'R001', 'class_roll_call' => 'absent'],
        ]);

        $this->assertSame(1, $count);
        $this->assertDatabaseHas('attendance_list', ['reg_number' => 'R001', 'class_roll_call' => 2]);
    }

    public function test_delete_by_class_term_session_segment_date_removes_records(): void
    {
        $date = now()->format('Y-m-d H:i:s');
        AttendanceRecord::query()->insert([
            'class' => 'SS1',
            'term' => '1',
            'session' => '2024',
            'segment' => 'AM',
            'name' => 'A',
            'reg_number' => 'R1',
            'class_roll_call' => 'present',
            'date_added' => $date,
        ]);

        $deleted = $this->service->deleteByClassTermSessionSegmentDate(
            'SS1', '1', '2024',
            now()->format('Y-m-d')
        );

        $this->assertSame(1, $deleted);
        $this->assertDatabaseMissing('attendance_list', ['reg_number' => 'R1', 'class' => 'SS1']);
    }
}
