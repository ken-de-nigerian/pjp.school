<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Enums\ResultStatus;
use App\Models\AnnualResult;
use App\Services\ResultService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultServiceTest extends TestCase
{
    use RefreshDatabase;

    private ResultService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ResultService::class);
    }

    public function test_has_uploaded_results_false_when_empty(): void
    {
        $this->assertFalse($this->service->hasUploadedResults('JSS 1', '1', '2024/2025', 'Math'));
    }

    public function test_has_uploaded_results_true_when_term_row_exists(): void
    {
        AnnualResult::query()->create([
            'studentId' => 1,
            'class' => 'JSS 1',
            'class_arm' => 'Junior',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
            'name' => 'A',
            'reg_number' => '1001',
            'segment' => 'First',
            'ca' => 0,
            'assignment' => 0,
            'exam' => 0,
            'total' => 0,
            'status' => 1,
            'date_added' => now(),
        ]);

        $this->assertTrue($this->service->hasUploadedResults('JSS 1', '1', '2024/2025', 'Math'));
    }

    public function test_upload_term_total_is_sum(): void
    {
        $results = [
            [
                'studentId' => 1,
                'class' => 'JSS 1',
                'term' => '1',
                'session' => '2024/2025',
                'subjects' => 'Math',
                'name' => 'John',
                'reg_number' => '1001',
                'ca' => 10,
                'assignment' => 10,
                'exam' => 20,
            ],
        ];

        $count = $this->service->bulkInsert($results);
        $this->assertSame(1, $count);
        $row = AnnualResult::query()->where('reg_number', '1001')->firstOrFail();
        $this->assertSame(40.0, (float) $row->total);
        $this->assertSame(ResultStatus::APPROVED->value, (int) $row->status);
    }

    public function test_bulk_insert_with_pending_status_for_teacher_upload(): void
    {
        $results = [
            [
                'studentId' => 1,
                'class' => 'JSS 1',
                'term' => '1',
                'session' => '2024/2025',
                'subjects' => 'Math',
                'name' => 'John',
                'reg_number' => '1001',
                'ca' => 5,
                'assignment' => 5,
                'exam' => 10,
            ],
        ];

        $count = $this->service->bulkInsert($results, ResultStatus::PENDING->value);
        $this->assertSame(1, $count);
        $row = AnnualResult::query()->where('reg_number', '1001')->firstOrFail();
        $this->assertSame(ResultStatus::PENDING->value, (int) $row->status);
    }

    public function test_get_uploaded_results_orders_by_name(): void
    {
        $seg = 'First';
        AnnualResult::query()->create([
            'studentId' => 1,
            'class' => 'JSS 1',
            'class_arm' => 'Junior',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
            'name' => 'Zara',
            'reg_number' => '1002',
            'segment' => $seg,
            'ca' => 0, 'assignment' => 0, 'exam' => 0, 'total' => 0, 'status' => 1, 'date_added' => now(),
        ]);
        AnnualResult::query()->create([
            'studentId' => 2,
            'class' => 'JSS 1',
            'class_arm' => 'Junior',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
            'name' => 'Alice',
            'reg_number' => '1001',
            'segment' => $seg,
            'ca' => 0, 'assignment' => 0, 'exam' => 0, 'total' => 0, 'status' => 1, 'date_added' => now(),
        ]);

        $collection = $this->service->getUploadedResults('JSS 1', '1', '2024/2025', 'Math');
        $this->assertSame('Alice', $collection->first()->name);
        $this->assertSame('Zara', $collection->last()->name);
    }

    public function test_edit_recalculates_total(): void
    {
        AnnualResult::query()->create([
            'studentId' => 1,
            'class' => 'JSS 1',
            'class_arm' => 'Junior',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
            'name' => 'A',
            'reg_number' => '1001',
            'segment' => 'First',
            'ca' => 5,
            'assignment' => 5,
            'exam' => 10,
            'total' => 20,
            'status' => 1,
            'date_added' => now(),
        ]);

        $updated = $this->service->editUploadedResult('1', 'JSS 1', '1', '2024/2025', 'Math', '1001', 10, 10, 20);
        $this->assertSame(1, $updated);
        $row = AnnualResult::query()->where('reg_number', '1001')->firstOrFail();
        $this->assertSame(40.0, (float) $row->total);
    }

    public function test_approve_by_ids(): void
    {
        $r1 = AnnualResult::query()->create([
            'studentId' => 1,
            'class' => 'JSS 1',
            'class_arm' => 'Junior',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
            'name' => 'A',
            'reg_number' => '1001',
            'segment' => 'First',
            'ca' => 0, 'assignment' => 0, 'exam' => 0, 'total' => 0, 'status' => 2, 'date_added' => now(),
        ]);

        $count = $this->service->approveByIds([$r1->id]);
        $this->assertSame(1, $count);
        $r1->refresh();
        $this->assertSame(1, (int) $r1->status);
    }

    public function test_get_uploaded_results_aggregates_legacy_segment_duplicates(): void
    {
        $base = [
            'studentId' => 1,
            'class' => 'JSS 1',
            'class_arm' => 'JSS 1',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
            'name' => 'A',
            'reg_number' => '1001',
            'status' => 1,
            'date_added' => now(),
        ];
        AnnualResult::query()->create(array_merge($base, [
            'segment' => '1',
            'ca' => 10,
            'assignment' => 10,
            'exam' => 20,
            'total' => 40,
        ]));
        AnnualResult::query()->create(array_merge($base, [
            'segment' => '2',
            'ca' => 20,
            'assignment' => 20,
            'exam' => 40,
            'total' => 80,
        ]));

        $collection = $this->service->getUploadedResults('JSS 1', '1', '2024/2025', 'Math');
        $this->assertCount(1, $collection);
        $row = $collection->first();
        $this->assertSame(60.0, (float) $row->total);
        $this->assertSame(15.0, (float) $row->ca);
        $this->assertSame(15.0, (float) $row->assignment);
        $this->assertSame(30.0, (float) $row->exam);
    }

    public function test_approve_by_ids_expands_legacy_segment_duplicates(): void
    {
        $base = [
            'studentId' => 1,
            'class' => 'JSS 1',
            'class_arm' => 'JSS 1',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
            'name' => 'A',
            'reg_number' => '1001',
            'ca' => 0,
            'assignment' => 0,
            'exam' => 0,
            'total' => 0,
            'status' => 2,
            'date_added' => now(),
        ];
        $r1 = AnnualResult::query()->create(array_merge($base, ['segment' => '1']));
        $r2 = AnnualResult::query()->create(array_merge($base, ['segment' => '2']));

        $count = $this->service->approveByIds([(int) $r1->id]);
        $this->assertSame(2, $count);
        $r1->refresh();
        $r2->refresh();
        $this->assertSame(1, (int) $r1->status);
        $this->assertSame(1, (int) $r2->status);
    }
}
