<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Support\Coercion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

final class AttendanceService
{
    /** @return Collection<int, AttendanceRecord> */
    public function getRecord(string $date, string $class, string $term, string $session): Collection
    {
        return AttendanceRecord::query()
            ->forClassTermSessionSegment($class, $term, $session)
            ->where('date_added', 'like', $date.'%')
            ->orderBy('name')
            ->get();
    }

    /** @param array<int, array<string, mixed>> $attendance
     * @throws Throwable
     */
    public function saveRecord(array $attendance): int
    {
        if (empty($attendance)) {
            return 0;
        }

        $currentDateTime = now()->toDateTimeString();
        $dateForDay = now()->format('Y-m-d');

        $noSegment = config('school.no_segment', 'No Segment');

        return (int) DB::transaction(function () use ($attendance, $dateForDay, $currentDateTime, $noSegment) {
            $affected = 0;
            foreach ($attendance as $row) {
                $classRollCall = strtolower(Coercion::string($row['class_roll_call'] ?? '')) === 'present' ? 1 : 2;
                $updated = AttendanceRecord::query()
                    ->where('class', $row['class'])
                    ->where('term', $row['term'])
                    ->where('session', $row['session'])
                    ->where('reg_number', $row['reg_number'])
                    ->where('date_added', 'like', $dateForDay.'%')
                    ->update([
                        'class_roll_call' => $classRollCall,
                        'name' => $row['name'] ?? '',
                        'date_added' => $currentDateTime,
                    ]);

                if ($updated > 0) {
                    $affected += $updated;
                } else {
                    AttendanceRecord::query()->insert([
                        'class' => $row['class'],
                        'term' => $row['term'],
                        'session' => $row['session'],
                        'segment' => $noSegment,
                        'name' => $row['name'] ?? '',
                        'reg_number' => $row['reg_number'],
                        'class_roll_call' => $classRollCall,
                        'date_added' => $currentDateTime,
                    ]);
                    $affected += 1;
                }
            }

            return $affected;
        });
    }

    /**
     * @param  array<int, array{reg_number: string, class_roll_call?: string}>  $updates
     */
    public function editRecord(
        string $class,
        string $term,
        string $session,
        string $date,
        array $updates
    ): int {
        if (empty($updates)) {
            return 0;
        }

        $updated = 0;
        $presentRegs = [];
        $absentRegs = [];

        foreach ($updates as $update) {
            $regNumber = $update['reg_number'];
            $status = strtolower($update['class_roll_call'] ?? '');

            if ($status === 'present') {
                $presentRegs[] = $regNumber;
            } else {
                $absentRegs[] = $regNumber;
            }
        }

        if (! empty($presentRegs)) {
            $updated += AttendanceRecord::query()
                ->forClassTermSessionSegment($class, $term, $session)
                ->where('date_added', 'like', $date.'%')
                ->whereIn('reg_number', $presentRegs)
                ->update(['class_roll_call' => 1]);
        }

        if (! empty($absentRegs)) {
            $updated += AttendanceRecord::query()
                ->forClassTermSessionSegment($class, $term, $session)
                ->where('date_added', 'like', $date.'%')
                ->whereIn('reg_number', $absentRegs)
                ->update(['class_roll_call' => 2]);
        }

        return $updated;
    }

    public function deleteByClassTermSessionSegmentDate(
        string $class,
        string $term,
        string $session,
        string $date
    ): int {
        $deleted = AttendanceRecord::query()
            ->forClassTermSessionSegment($class, $term, $session)
            ->where('date_added', 'like', $date.'%')
            ->delete();

        return is_int($deleted) ? $deleted : 0;
    }

    public function deleteOneRecord(
        string $regNumber,
        string $class,
        string $term,
        string $session,
        string $date
    ): int {
        $deleted = AttendanceRecord::query()
            ->forClassTermSessionSegment($class, $term, $session)
            ->where('reg_number', $regNumber)
            ->where('date_added', 'like', $date.'%')
            ->delete();

        return is_int($deleted) ? $deleted : 0;
    }
}
