<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Behavioral;
use App\Services\BehavioralService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BehavioralServiceTest extends TestCase
{
    use RefreshDatabase;

    private BehavioralService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BehavioralService;
    }

    public function test_has_behavioral_analysis_false_when_empty(): void
    {
        $this->assertFalse($this->service->hasBehavioralAnalysis('JSS 1', '1', '2024/2025'));
    }

    public function test_has_behavioral_analysis_true_when_exists(): void
    {
        Behavioral::query()->create([
            'class' => 'JSS 1',
            'term' => '1',
            'session' => '2024/2025',
            'segment' => 'First',
            'name' => 'A',
            'reg_number' => '1001',
            'neatness' => '1',
            'music' => '1',
            'sports' => '1',
            'attentiveness' => '1',
            'punctuality' => '1',
            'health' => '1',
            'politeness' => '1',
            'date_added' => now(),
        ]);

        $this->assertTrue($this->service->hasBehavioralAnalysis('JSS 1', '1', '2024/2025'));
    }

    public function test_bulk_insert_creates_records(): void
    {
        $data = [
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
        ];

        $count = $this->service->bulkInsert($data);
        $this->assertSame(1, $count);
        $this->assertDatabaseHas('behavioral', ['reg_number' => '1001', 'class' => 'JSS 1']);
    }

    public function test_get_record_orders_by_name(): void
    {
        Behavioral::query()->create([
            'class' => 'JSS 1', 'term' => '1', 'session' => '2024/2025', 'segment' => 'First',
            'name' => 'Zara', 'reg_number' => '1002', 'neatness' => '1', 'music' => '1', 'sports' => '1',
            'attentiveness' => '1', 'punctuality' => '1', 'health' => '1', 'politeness' => '1', 'date_added' => now(),
        ]);
        Behavioral::query()->create([
            'class' => 'JSS 1', 'term' => '1', 'session' => '2024/2025', 'segment' => 'First',
            'name' => 'Alice', 'reg_number' => '1001', 'neatness' => '1', 'music' => '1', 'sports' => '1',
            'attentiveness' => '1', 'punctuality' => '1', 'health' => '1', 'politeness' => '1', 'date_added' => now(),
        ]);

        $records = $this->service->getRecord('JSS 1', '1', '2024/2025');
        $first = $records->first();
        $last = $records->last();
        $this->assertNotNull($first);
        $this->assertNotNull($last);
        $this->assertSame('Alice', $first->name);
        $this->assertSame('Zara', $last->name);
    }

    public function test_edit_record_updates_one_row(): void
    {
        Behavioral::query()->create([
            'class' => 'JSS 1', 'term' => '1', 'session' => '2024/2025', 'segment' => 'First',
            'name' => 'Alice', 'reg_number' => '1001', 'neatness' => 'A', 'music' => 'A', 'sports' => 'A',
            'attentiveness' => 'A', 'punctuality' => 'A', 'health' => 'A', 'politeness' => 'A', 'date_added' => now(),
        ]);

        $updated = $this->service->editRecord('1001', 'JSS 1', '1', '2024/2025', 'B', 'B', 'B', 'B', 'B', 'B', 'B');
        $this->assertSame(1, $updated);
        $row = Behavioral::query()->where('reg_number', '1001')->firstOrFail();
        $this->assertSame('B', $row->neatness);
        $this->assertSame('B', $row->politeness);
    }
}
