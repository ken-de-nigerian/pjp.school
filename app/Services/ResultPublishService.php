<?php

namespace App\Services;

use App\Models\AnnualResult;
use App\Models\Notification;
use App\Models\Position;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ResultPublishService
{
    /**
     * Publish results for a class/term/session. Returns response array (no HTTP).
     * Optimized: single query for all results per class/term/session instead of N+1.
     */
    public function publish(string $class, string $term, string $session, string $adminName): array
    {
        $students = Student::query()
            ->active()
            ->byClassArm($class)
            ->get(['reg_number', 'subjects']);

        if ($students->isEmpty()) {
            return [
                'status' => 'error',
                'message' => $term.' results for '.$class.' haven\'t been uploaded yet.',
            ];
        }

        // Single query: all annual_result rows for this class/term/session
        $allResults = AnnualResult::query()
            ->forClassTermSession($class, $term, $session)
            ->approved()
            ->get()
            ->groupBy('reg_number');

        $insertData = [];
        $hasUnverified = false;

        foreach ($students as $student) {
            $results = $allResults->get($student->reg_number, collect());

            if ($results->isEmpty()) {
                continue;
            }

            foreach ($results as $result) {
                if (in_array((int) $result->status, [2, 3], true)) {
                    $hasUnverified = true;
                    break 2;
                }
            }

            $numSubjects = count(array_filter(explode(',', $student->subjects ?? '')));
            if ($numSubjects <= 0) {
                continue;
            }

            $total = $results->sum('total');
            $average = $total / $numSubjects;
            $first = $results->first();

            $insertData[] = [
                'reg_number' => $first->reg_number,
                'name' => $first->name,
                'class' => $first->class_arm,
                'term' => $first->term,
                'session' => $first->session,
                'students_sub_total' => $total,
                'students_sub_average' => $average,
                'class_position' => 0,
                'status' => 0,
                'date_added' => now()->format('Y-m-d H:i:s'),
            ];
        }

        if ($hasUnverified) {
            return [
                'status' => 'error',
                'message' => 'Some results in '.$class.' are pending approval, please approve and try again.',
            ];
        }

        if (empty($insertData)) {
            return [
                'status' => 'error',
                'message' => $term.' results for '.$class.' haven\'t been uploaded yet.',
            ];
        }

        DB::transaction(function () use ($insertData, $class, $term, $session, $adminName) {
            Notification::query()->create([
                'title' => 'Results Published',
                'message' => $adminName.' has published '.$term.' results for: '.$class,
                'date_added' => now()->format('Y-m-d H:i:s'),
            ]);

            Position::query()->insert($insertData);

            $positions = Position::query()
                ->forClassTermSession($class, $term, $session)
                ->orderByDesc('students_sub_average')
                ->get(['reg_number', 'class', 'term', 'session']);

            $position = 1;
            foreach ($positions as $pos) {
                Position::query()
                    ->where('reg_number', $pos->reg_number)
                    ->where('class', $pos->class)
                    ->where('term', $pos->term)
                    ->where('session', $pos->session)
                    ->update(['class_position' => $position]);
                $position++;
            }
        });

        return [
            'status' => 'success',
            'message' => $term.' results for '.$class.' have been successfully published.',
        ];
    }
}
