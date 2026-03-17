<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Behavioral;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class BehavioralService
{
    public function hasBehavioralAnalysis(string $class, string $term, string $session, string $segment): bool
    {
        return Behavioral::query()
            ->forClassTermSessionSegment($class, $term, $session, $segment)
            ->exists();
    }

    public function getRecord(string $class, string $term, string $session, string $segment): Collection
    {
        $records = Behavioral::query()
            ->forClassTermSessionSegment($class, $term, $session, $segment)
            ->orderBy('name')
            ->get();

        $regNumbers = $records->pluck('reg_number')->unique()->filter()->values();
        if ($regNumbers->isEmpty()) {
            return $records;
        }

        /** @var Collection<string, string|null> $imagelocationByReg */
        $imagelocationByReg = Student::query()
            ->whereIn('reg_number', $regNumbers->all())
            ->pluck('imagelocation', 'reg_number');

        return $records->map(static function (Behavioral $record) use ($imagelocationByReg): Behavioral {
            $record->setAttribute(
                'imagelocation',
                $imagelocationByReg->get($record->reg_number)
            );

            return $record;
        });
    }

    /**
     * @throws Throwable
     */
    public function bulkInsert(array $behavioral): int
    {
        $rows = [];
        $currentDateTime = now()->toDateTimeString();

        $noSegment = config('school.no_segment', 'No Segment');

        foreach ($behavioral as $row) {
            $rows[] = [
                'class' => $row['class'] ?? '',
                'term' => $row['term'] ?? '',
                'session' => $row['session'] ?? '',
                'segment' => $noSegment,
                'name' => $row['name'] ?? '',
                'reg_number' => $row['reg_number'] ?? '',
                'neatness' => $row['neatness'] ?? '',
                'music' => $row['music'] ?? '',
                'sports' => $row['sports'] ?? '',
                'attentiveness' => $row['attentiveness'] ?? '',
                'punctuality' => $row['punctuality'] ?? '',
                'health' => $row['health'] ?? '',
                'politeness' => $row['politeness'] ?? '',
                'date_added' => $currentDateTime,
            ];
        }

        if (empty($rows)) {
            return 0;
        }

        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                Behavioral::query()->create($row);
            }
        });

        return count($rows);
    }

    public function editRecord(
        string $reg_number,
        string $class,
        string $term,
        string $session,
        string $neatness,
        string $music,
        string $sports,
        string $attentiveness,
        string $punctuality,
        string $health,
        string $politeness
    ): int {
        return Behavioral::query()
            ->where('reg_number', $reg_number)
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->update([
                'neatness' => $neatness,
                'music' => $music,
                'sports' => $sports,
                'attentiveness' => $attentiveness,
                'punctuality' => $punctuality,
                'health' => $health,
                'politeness' => $politeness,
            ]);
    }

    public function deleteRecord(string $class, string $term, string $session, string $segment): int
    {
        return Behavioral::query()
            ->forClassTermSessionSegment($class, $term, $session, $segment)
            ->delete();
    }

    public function deleteOneRecord(string $reg_number, string $class, string $term, string $session, string $segment): int
    {
        return Behavioral::query()
            ->where('reg_number', $reg_number)
            ->forClassTermSessionSegment($class, $term, $session, $segment)
            ->delete();
    }
}
