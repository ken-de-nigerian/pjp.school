<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResultPublishTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Admin::query()->firstOrCreate(
            ['adminId' => 'result-admin'],
            [
                'name' => 'Result Admin',
                'email' => 'result@test.local',
                'password' => Hash::make('password'),
                'user_type' => 1,
            ]
        );
    }

    public function test_guest_cannot_publish_results(): void
    {
        $response = $this->postJson(route('admin.results.publish'), [
            'class' => 'SS1',
            'term' => '1',
            'session' => '2024/2025',
        ]);

        $response->assertStatus(401);
    }

    public function test_admin_publish_requires_class_term_session(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')
            ->postJson(route('admin.results.publish'), [
                '_token' => csrf_token(),
            ]);

        $response->assertInvalid(['class', 'term', 'session']);
    }
}
