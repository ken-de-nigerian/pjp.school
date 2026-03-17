<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PinCode;
use App\Models\UnusedPin;
use App\Models\UsedPin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Random\RandomException;
use Throwable;

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

    public function getUnused(string $session): Collection
    {
        return UnusedPin::query()
            ->where('session', $session)
            ->orderBy('id')
            ->get();
    }

    public function getUnusedPaginated(string $session, int $perPage = 50, int $page = 1): LengthAwarePaginator
    {
        return UnusedPin::query()
            ->where('session', $session)
            ->orderBy('id')
            ->paginate(perPage: $perPage, page: $page);
    }

    public function getUsed(string $session, int $perPage = 200, int $page = 1): LengthAwarePaginator
    {
        return UsedPin::query()
            ->where('session', $session)
            ->with('student')
            ->orderByDesc('time_used')
            ->paginate(perPage: $perPage, page: $page);
    }

    public function generatePinValues(int $numPins): array
    {
        $time = date('s');
        $pins = [];
        for ($i = 0; $i < $numPins; $i++) {
            $pins[] = $time . $this->rnd(14, false, false, true);
        }
        return $pins;
    }

    private function rnd(int $length, bool $lower, bool $upper, bool $numbers): string
    {
        $pool = "";
        $result = "";

        if($lower){
            $pool .= "abcdefghijklmnopqrstuvwxyz";
        }

        if($upper){
            $pool .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }

        if($numbers){
            $pool .= "01234567890";
        }

        $cc = 0;
        while($cc < $length){
            $result .= $pool[mt_rand(0, strlen($pool)-1)];
            $cc++;
        }
        return $result;
    }

    /**
     * @throws RandomException|Throwable
     */
    public function addPins(array $pins, string $session): int
    {
        $now = now()->format('Y-m-d H:i:s');

        return (int) DB::transaction(function () use ($pins, $session, $now) {
            $inserted = 0;
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
        });
    }

    public function hasPin(string $pin): bool
    {
        return PinCode::query()->where('pin', $pin)->exists();
    }

    public function hasCard(string $pin): bool
    {
        return UsedPin::query()->where('pins', $pin)->exists();
    }

    public function usedPinData(string $pin): ?UsedPin
    {
        return UsedPin::query()->where('pins', $pin)->first();
    }

    /**
     * @throws Throwable
     */
    public function markUsedInsert(
        string $pin,
        string $regNumber,
        int $usedCount,
        string $class,
        string $term,
        string $session
    ): int {
        DB::transaction(function () use ($pin, $regNumber, $usedCount, $class, $term, $session) {
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
        });

        return 1;
    }

    public function markUsedUpdate(
        string $pin,
        string $regNumber,
        int $usedCount,
        string $class,
        string $session
    ): int {
        return UsedPin::query()
            ->where('pins', $pin)
            ->where('reg_number', $regNumber)
            ->where('class', $class)
            ->where('session', $session)
            ->update(['used_count' => $usedCount]);
    }

    /**
     * @throws RandomException
     */
    private function uniqueId(): string
    {
        return substr(number_format(microtime(true) * random_int(1, 99999), 0, '', ''), 0, 12);
    }
}
