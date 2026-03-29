<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\StudentService;
use App\Support\Coercion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentServiceTest extends TestCase
{
    use RefreshDatabase;

    private StudentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StudentService;
    }

    public function test_get_classes_with_counts_returns_empty_when_no_classes(): void
    {
        $result = $this->service->getClassesWithCounts();

        $this->assertEmpty($result);
    }

    public function test_get_classes_with_counts_includes_class_and_user_count(): void
    {
        SchoolClass::query()->create(['class_name' => 'SS1', 'time_added' => now()]);
        Student::query()->create([
            'reg_number' => 'R1',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'SS1',
            'status' => 2,
        ]);

        $result = $this->service->getClassesWithCounts();

        $this->assertCount(1, $result);
        $this->assertSame('SS1', $result[0]['class_name']);
        $this->assertSame(1, $result[0]['user_count']);
    }

    public function test_get_house_counts_returns_empty_when_no_houses_configured(): void
    {
        config(['school.houses' => []]);
        $result = $this->service->getHouseCounts();

        $this->assertEmpty($result);
    }

    public function test_get_house_counts_excludes_left_and_graduated_class(): void
    {
        config(['school.houses' => ['Red']]);
        foreach (
            [
                ['reg_number' => 'R1', 'class' => 'JSS 1', 'house' => 'Red'],
                ['reg_number' => 'R2', 'class' => 'Graduated', 'house' => 'Red'],
                ['reg_number' => 'R3', 'class' => 'Left', 'house' => 'Red'],
                ['reg_number' => 'R4', 'class' => 'left-school', 'house' => 'Red'],
                ['reg_number' => 'R5', 'class' => 'LEFT', 'house' => 'Red'],
            ] as $row
        ) {
            Student::query()->create([
                'reg_number' => $row['reg_number'],
                'firstname' => 'A',
                'lastname' => 'B',
                'class' => $row['class'],
                'house' => $row['house'],
                'status' => 2,
            ]);
        }

        $result = $this->service->getHouseCounts();

        $this->assertCount(1, $result);
        $this->assertSame('Red', $result[0]['house']);
        $this->assertSame(1, $result[0]['user_count']);
    }

    public function test_get_by_id_returns_student(): void
    {
        $student = Student::query()->create([
            'reg_number' => 'R1',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'JSS 1',
            'status' => 2,
        ]);

        $this->assertSame($student->id, $this->service->getById($student->id)?->id);
        $this->assertNull($this->service->getById(99999));
    }

    public function test_get_by_reg_number_returns_only_active(): void
    {
        Student::query()->create([
            'reg_number' => 'R1',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'JSS 1',
            'status' => 1,
        ]);

        $this->assertNull($this->service->getByRegNumber('R1'));
    }

    public function test_create_sets_class_arm_and_status(): void
    {
        SchoolClass::query()->create(['class_name' => 'JSS 1', 'time_added' => now()]);

        $student = $this->service->create([
            'reg_number' => '1001',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'JSS 1',
        ], null);

        $this->assertSame(2, $student->status);
        $this->assertNotNull($student->class_arm);
        $this->assertSame('default.png', $student->imagelocation);
    }

    public function test_promote_updates_class(): void
    {
        SchoolClass::query()->create(['class_name' => 'JSS 2', 'time_added' => now()]);
        $student = Student::query()->create([
            'reg_number' => 'R1',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'JSS 1',
            'status' => 2,
        ]);

        $this->service->promote('JSS 1', 'JSS 2', [$student->id]);

        $s = Student::where('reg_number', 'R1')->firstOrFail();
        $this->assertSame('JSS 2', $s->class);
    }

    public function test_demote_by_ids(): void
    {
        SchoolClass::query()->create(['class_name' => 'JSS 1', 'time_added' => now()]);
        $s1 = Student::query()->create([
            'reg_number' => 'R1',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'JSS 2',
            'status' => 2,
        ]);

        $this->service->demote('JSS 1', [$s1->id]);

        $s1->refresh();
        $this->assertSame('JSS 1', $s1->class);
    }

    public function test_left_school_includes_class_column_leavers_bucketed_by_time_of_reg(): void
    {
        Student::query()->create([
            'reg_number' => 'L1',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'left-school',
            'status' => 2,
            'time_of_reg' => '2024-06-01 10:00:00',
        ]);

        $rows = $this->service->getLeftSchoolYearsWithCounts();
        $byYear = collect($rows)->keyBy('year');
        $this->assertSame(1, Coercion::int($byYear['2024']['user_count'] ?? 0));

        $listed = $this->service->getStudentsWhoLeftSchool('2024');
        $this->assertCount(1, $listed);
        $this->assertSame('L1', $listed->first()?->reg_number);
    }

    public function test_left_school_class_leaver_without_dates_is_undated_year(): void
    {
        Student::query()->create([
            'reg_number' => 'L2',
            'firstname' => 'C',
            'lastname' => 'D',
            'class' => 'Left',
            'status' => 2,
        ]);

        $rows = $this->service->getLeftSchoolYearsWithCounts();
        $this->assertCount(1, $rows);
        $this->assertSame(Student::LEFT_SCHOOL_UNDATED_YEAR, $rows[0]['year']);
        $this->assertSame(1, $rows[0]['user_count']);

        $listed = $this->service->getStudentsWhoLeftSchool(Student::LEFT_SCHOOL_UNDATED_YEAR);
        $this->assertCount(1, $listed);
    }

    public function test_left_school_excludes_graduated_class(): void
    {
        Student::query()->create([
            'reg_number' => 'G1',
            'firstname' => 'E',
            'lastname' => 'F',
            'class' => 'Graduated',
            'status' => 2,
        ]);

        $this->assertSame([], $this->service->getLeftSchoolYearsWithCounts());
    }

    public function test_toggle_status_sets_left_school_date(): void
    {
        $s = Student::query()->create([
            'reg_number' => 'R1',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'JSS 1',
            'status' => 2,
        ]);

        $this->service->toggleStatus($s->id, 1, 'Junior');

        $s->refresh();
        $this->assertSame(1, $s->status);
        $this->assertNotNull($s->left_school_date);
    }

    public function test_delete_removes_student(): void
    {
        $s = Student::query()->create([
            'reg_number' => 'R1',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'JSS 1',
            'status' => 2,
        ]);

        $this->service->delete($s->id);

        $this->assertDatabaseMissing('students', ['id' => $s->id]);
    }

    public function test_add_class_creates_and_has_class(): void
    {
        $this->assertFalse($this->service->hasClass('SS 3'));
        $this->service->addClass('SS 3');
        $this->assertTrue($this->service->hasClass('SS 3'));
        $this->assertDatabaseHas('classes', ['class_name' => 'SS 3']);
    }
}
