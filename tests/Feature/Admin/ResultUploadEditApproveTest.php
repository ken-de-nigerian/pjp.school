<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\AnnualResult;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResultUploadEditApproveTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Role::query()->firstOrCreate(['id' => 1], ['name' => 'Admin', 'permissions' => null]);
        $this->admin = Admin::query()->firstOrCreate(
            ['email' => 'resultupload@test.local'],
            [
                'name' => 'Result Admin',
                'password' => Hash::make('password'),
                'user_type' => 1,
                'joined' => now(),
            ]
        );
    }

    public function test_upload_term_inserts_full_total(): void
    {
        $results = [
            [
                'studentId' => 1,
                'class' => 'JSS 1',
                'term' => '1',
                'session' => '2024/2025',
                'subjects' => 'Mathematics',
                'name' => 'John Doe',
                'reg_number' => '1001',
                'ca' => 10,
                'assignment' => 10,
                'exam' => 20,
            ],
        ];

        $response = $this->actingAs($this->admin, 'admin')->postJson(route('admin.results.upload-term'), [
            'results' => $results,
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');
        $this->assertDatabaseHas('annual_result', [
            'reg_number' => '1001',
            'ca' => 10,
            'assignment' => 10,
            'exam' => 20,
        ]);
        $row = AnnualResult::query()->where('reg_number', '1001')->first();
        $this->assertNotNull($row);
        $this->assertSame(40.0, round((float) $row->total, 1));
    }

    public function test_upload_duplicate_returns_error(): void
    {
        AnnualResult::query()->create([
            'studentId' => 1,
            'class' => 'JSS 1',
            'class_arm' => 'Junior',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
            'name' => 'J',
            'reg_number' => '1001',
            'segment' => 'First',
            'ca' => 0,
            'assignment' => 0,
            'exam' => 0,
            'total' => 0,
            'status' => 1,
            'date_added' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')->postJson(route('admin.results.upload-term'), [
            'results' => [
                [
                    'studentId' => 1,
                    'class' => 'JSS 1',
                    'term' => '1',
                    'session' => '2024/2025',
                    'subjects' => 'Math',
                    'name' => 'J',
                    'reg_number' => '1001',
                    'ca' => 5,
                    'assignment' => 5,
                    'exam' => 10,
                ],
            ],
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'error');
    }

    public function test_get_uploaded_results_returns_filtered(): void
    {
        AnnualResult::query()->create([
            'studentId' => 1,
            'class' => 'JSS 1',
            'class_arm' => 'Junior',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
            'name' => 'Alice',
            'reg_number' => '1001',
            'segment' => 'First',
            'ca' => 10,
            'assignment' => 10,
            'exam' => 20,
            'total' => 40,
            'status' => 1,
            'date_added' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.results.uploaded', [
            'class' => 'JSS 1',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
        ]));

        $response->assertOk();
        $response->assertViewHas('results');
        $results = $response->viewData('results');
        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(1, $results);
    }

    public function test_edit_recalculates_total_term_weight(): void
    {
        $row = AnnualResult::query()->create([
            'studentId' => 1,
            'class' => 'JSS 1',
            'class_arm' => 'Junior',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
            'name' => 'Alice',
            'reg_number' => '1001',
            'segment' => 'First',
            'ca' => 10,
            'assignment' => 10,
            'exam' => 20,
            'total' => 40,
            'status' => 1,
            'date_added' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')->putJson(route('admin.results.edit'), [
            'studentId' => 1,
            'class' => 'JSS 1',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
            'reg_number' => '1001',
            'ca' => 15,
            'assignment' => 15,
            'exam' => 30,
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');
        $row->refresh();
        $this->assertSame(60.0, round((float) $row->total, 1));
    }

    public function test_approve_sets_status_to_one(): void
    {
        $row = AnnualResult::query()->create([
            'studentId' => 1,
            'class' => 'JSS 1',
            'class_arm' => 'Junior',
            'term' => '1',
            'session' => '2024/2025',
            'subjects' => 'Math',
            'name' => 'Alice',
            'reg_number' => '1001',
            'segment' => 'First',
            'ca' => 10,
            'assignment' => 10,
            'exam' => 20,
            'total' => 40,
            'status' => 2,
            'date_added' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')->postJson(route('admin.results.approve'), [
            'selectedRows' => [$row->id],
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');
        $row->refresh();
        $this->assertSame(1, (int) $row->status);
    }
}
