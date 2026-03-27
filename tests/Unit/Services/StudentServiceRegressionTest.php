<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression: assert class counts structure and logic match legacy (getAllClass).
 * Legacy: classes ordered by class_name ASC; each class has user_count from active students (status=2) with class column exploded by comma.
 */
class StudentServiceRegressionTest extends TestCase
{
    use RefreshDatabase;

    private StudentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StudentService;
    }

    public function test_get_classes_with_counts_returns_legacy_structure(): void
    {
        SchoolClass::query()->create(['class_name' => 'SS1', 'time_added' => now()]);
        SchoolClass::query()->create(['class_name' => 'SS2', 'time_added' => now()]);
        Student::query()->create([
            'reg_number' => 'R1',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'SS1',
            'status' => 2,
        ]);

        $result = $this->service->getClassesWithCounts();

        $this->assertCount(2, $result);
        $this->assertArrayHasKey('class_name', $result[0]);
        $this->assertArrayHasKey('user_count', $result[0]);
        $this->assertSame('SS1', $result[0]['class_name']);
        $this->assertSame('SS2', $result[1]['class_name']);
        $this->assertSame(1, $result[0]['user_count']);
        $this->assertSame(0, $result[1]['user_count']);
    }

    public function test_get_house_counts_returns_legacy_structure(): void
    {
        config(['school.houses' => ['HouseA', 'HouseB']]);
        Student::query()->create([
            'reg_number' => 'R1',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'SS1',
            'house' => 'HouseA',
            'status' => 2,
        ]);

        $result = $this->service->getHouseCounts();

        $this->assertCount(2, $result);
        $this->assertSame('HouseA', $result[0]['house']);
        $this->assertSame(1, $result[0]['user_count']);
        $this->assertSame('HouseB', $result[1]['house']);
        $this->assertSame(0, $result[1]['user_count']);
    }
}
