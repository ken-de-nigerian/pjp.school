<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\PinCode;
use App\Models\UnusedPin;
use App\Models\UsedPin;
use App\Services\PinService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PinServiceTest extends TestCase
{
    use RefreshDatabase;

    private PinService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PinService;
    }

    public function test_count_unused_and_used(): void
    {
        UnusedPin::query()->create(['session' => '2024', 'pins' => 'P1', 'upload_date' => now()]);
        UnusedPin::query()->create(['session' => '2024', 'pins' => 'P2', 'upload_date' => now()]);
        UsedPin::query()->create([
            'pins' => 'P3', 'reg_number' => 'R1', 'used_count' => 1, 'class' => 'SS1',
            'term' => '1', 'session' => '2024', 'time_used' => now(),
        ]);

        $this->assertSame(2, $this->service->countUnused('2024'));
        $this->assertSame(1, $this->service->countUsed('2024'));
    }

    public function test_has_pin_and_has_card(): void
    {
        PinCode::query()->create(['pin' => 'VALID', 'session' => '2024', 'upload_date' => now()]);
        UnusedPin::query()->create(['pins' => 'VALID', 'session' => '2024', 'upload_date' => now()]);

        $this->assertTrue($this->service->hasPin('VALID'));
        $this->assertFalse($this->service->hasCard('VALID'));

        UsedPin::query()->create([
            'pins' => 'VALID', 'reg_number' => 'R1', 'used_count' => 1, 'class' => 'SS1',
            'term' => '1', 'session' => '2024', 'time_used' => now(),
        ]);
        UnusedPin::query()->where('pins', 'VALID')->delete();

        $this->assertTrue($this->service->hasCard('VALID'));
    }

    public function test_add_pins_inserts_into_pin_code_and_unused_pins(): void
    {
        $count = $this->service->addPins(['ABC', 'DEF'], '2024/2025');

        $this->assertSame(2, $count);
        $this->assertDatabaseHas('pin_code', ['pin' => 'ABC']);
        $this->assertDatabaseHas('unused_pins', ['pins' => 'ABC', 'session' => '2024/2025']);
    }

    public function test_add_pins_skips_duplicate_pin(): void
    {
        PinCode::query()->create(['pin' => 'EXIST', 'session' => '2024', 'upload_date' => now()]);

        $count = $this->service->addPins(['EXIST', 'NEWONE'], '2024');

        $this->assertSame(1, $count);
        $this->assertDatabaseCount('pin_code', 2);
        $this->assertDatabaseHas('unused_pins', ['pins' => 'NEWONE']);
    }

    public function test_mark_used_insert_moves_to_used_and_removes_from_unused(): void
    {
        PinCode::query()->create(['pin' => 'P1', 'session' => '2024', 'upload_date' => now()]);
        UnusedPin::query()->create(['pins' => 'P1', 'session' => '2024', 'upload_date' => now()]);

        $n = $this->service->markUsedInsert('P1', 'R001', 1, 'SS1', '1', '2024');

        $this->assertSame(1, $n);
        $this->assertDatabaseHas('used_pins', ['pins' => 'P1', 'reg_number' => 'R001']);
        $this->assertDatabaseMissing('unused_pins', ['pins' => 'P1']);
    }

    public function test_mark_used_update_increments_count(): void
    {
        UsedPin::query()->create([
            'pins' => 'P2', 'reg_number' => 'R2', 'used_count' => 2, 'class' => 'SS1',
            'term' => '1', 'session' => '2024', 'time_used' => now(),
        ]);

        $n = $this->service->markUsedUpdate('P2', 'R2', 3, 'SS1', '2024');

        $this->assertSame(1, $n);
        $this->assertDatabaseHas('used_pins', ['pins' => 'P2', 'used_count' => 3]);
    }
}
