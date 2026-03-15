<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\AcademicSession;
use App\Models\Setting;
use App\Services\AcademicSessionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicSessionServiceTest extends TestCase
{
    use RefreshDatabase;

    private AcademicSessionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::query()->firstOrCreate(['id' => 1], ['name' => 'School', 'session' => '2024/2025']);
        $this->service = new AcademicSessionService;
    }

    public function test_list_returns_sessions_ordered_by_year(): void
    {
        AcademicSession::query()->create(['year' => '2025/2026']);
        AcademicSession::query()->create(['year' => '2024/2025']);

        $list = $this->service->list();

        $this->assertCount(2, $list);
        $this->assertSame('2024/2025', $list->first()->year);
    }

    public function test_create_and_find(): void
    {
        $created = $this->service->create('2026/2027');
        $this->assertSame('2026/2027', $created->year);

        $found = $this->service->find($created->id);
        $this->assertNotNull($found);
        $this->assertSame('2026/2027', $found->year);
    }

    public function test_update(): void
    {
        $session = AcademicSession::query()->create(['year' => '2020/2021']);
        $this->service->update($session->id, '2020/2022');
        $session->refresh();
        $this->assertSame('2020/2022', $session->year);
    }

    public function test_activate_sets_settings_session(): void
    {
        $session = AcademicSession::query()->create(['year' => '2027/2028']);
        $ok = $this->service->activate($session->id);
        $this->assertTrue($ok);
        $this->assertSame('2027/2028', $this->service->getCurrentSessionYear());
    }

    public function test_delete(): void
    {
        $session = AcademicSession::query()->create(['year' => '2021/2022']);
        $n = $this->service->delete($session->id);
        $this->assertSame(1, $n);
        $this->assertDatabaseMissing('academic_sessions', ['id' => $session->id]);
    }
}
