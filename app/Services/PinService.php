<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PinCode;
use App\Models\UnusedPin;
use App\Models\UsedPin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Replicates legacy Card (addPin, countAllUnUsedCards, countUsedCards, getUnUsedCards, getUsedCards)
 * and Results (hasPin, hasCard, usedPinData, used_count_insert, used_count_update).
 */
class PinService
{
    public function countUnused(string $session): int
    {
        return UnusedPin::query()->where('session', $session)->count();
    }

    public function countUsed(string $session): int
    {
        return UsedPin::query()->where('session', $session)->count();
    }

    /** Legacy: Card::getUnUsedCards */
    public function getUnused(string $session): Collection
    {
        return UnusedPin::query()->where('session', $session)->get();
    }

    /** Legacy: Card::getUsedCards — ORDER time_used DESC, paginated */
    public function getUsed(string $session, int $perPage = 200, int $page = 1): LengthAwarePaginator
    {
        return UsedPin::query()
            ->where('session', $session)
            ->orderByDesc('time_used')
            ->paginate(perPage: $perPage, page: $page);
    }

    /**
     * Legacy: Card::addPin — insert into pin_code and unused_pins; skip if pin already in pin_code.
     * Returns number of pins actually added.
     */
    public function addPins(array $pins, string $session): int
    {
        $inserted = 0;
        $now = now()->format('Y-m-d H:i:s');

        foreach ($pins as $pin) {
            $pin = trim((string) $pin);
            if ($pin === '') {
                continue;
            }
            if (PinCode::query()->where('pin', $pin)->exists()) {
                continue;
            }
            $serialNumber = $this->uniqueId();
            PinCode::query()->create([
                'session' => $session,
                'pin' => $pin,
                'serial_number' => $serialNumber,
                'upload_date' => $now,
            ]);
            UnusedPin::query()->create([
                'session' => $session,
                'pins' => $pin,
                'serial_number' => $this->uniqueId(),
                'upload_date' => $now,
            ]);
            $inserted++;
        }

        return $inserted;
    }

    /** Legacy: Results::hasPin — pin exists in pin_code */
    public function hasPin(string $pin): bool
    {
        return PinCode::query()->where('pin', $pin)->exists();
    }

    /** Legacy: Results::hasCard — pin already in used_pins */
    public function hasCard(string $pin): bool
    {
        return UsedPin::query()->where('pins', $pin)->exists();
    }

    /** Legacy: Results::usedPinData — get used_pins row by pins */
    public function usedPinData(string $pin): ?UsedPin
    {
        return UsedPin::query()->where('pins', $pin)->first();
    }

    /**
     * Legacy: Results::used_count_insert — insert into used_pins and delete from unused_pins.
     * Call when pin is used for the first time.
     */
    public function markUsedInsert(
        string $pin,
        string $regNumber,
        int $usedCount,
        string $class,
        string $term,
        string $session
    ): int {
        UsedPin::query()->create([
            'pins' => $pin,
            'reg_number' => $regNumber,
            'used_count' => $usedCount,
            'class' => $class,
            'term' => $term,
            'session' => $session,
            'time_used' => now()->format('Y-m-d H:i:s'),
        ]);
        UnusedPin::query()->where('pins', $pin)->delete();

        return 1;
    }

    /**
     * Legacy: Results::used_count_update — update used_count for existing used_pins row.
     */
    public function markUsedUpdate(
        string $pin,
        string $regNumber,
        int $usedCount,
        string $class,
        string $session
    ): int {
        return (int) UsedPin::query()
            ->where('pins', $pin)
            ->where('reg_number', $regNumber)
            ->where('class', $class)
            ->where('session', $session)
            ->update(['used_count' => $usedCount]);
    }

    private function uniqueId(): string
    {
        return substr(number_format(microtime(true) * random_int(1, 99999), 0, '', ''), 0, 12);
    }
}
