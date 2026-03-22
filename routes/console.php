<?php

use App\Models\AnnualResult;
use App\Support\AnnualResultAggregation;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('results:report-annual-duplicates', function (): void {
    $groups = DB::table('annual_result')
        ->selectRaw('reg_number, subjects, class_arm, term, session, COUNT(*) as cnt')
        ->groupBy('reg_number', 'subjects', 'class_arm', 'term', 'session')
        ->having('cnt', '>', 1)
        ->orderByDesc('cnt')
        ->limit(500)
        ->get();

    $this->info('Duplicate subject groups (same reg_number + subject + class_arm + term + session): '.$groups->count());
    foreach ($groups as $g) {
        $this->line(sprintf(
            '%s | %s | %s | %s | %s → %d rows',
            $g->reg_number,
            $g->subjects,
            $g->class_arm,
            $g->term,
            $g->session,
            (int) $g->cnt
        ));
    }

    if ($groups->isEmpty()) {
        $this->info('No duplicate groups found.');
    }
})->purpose('List annual_result rows that share the same student/subject/context (legacy segment duplicates)');

Artisan::command('results:collapse-annual-duplicates {--dry-run : Preview only}', function (): void {
    if (! $this->option('dry-run')) {
        $this->error('Refusing to modify data without --dry-run first. Run with --dry-run to preview, then implement a verified migration if needed.');

        return;
    }

    $dupCount = DB::table('annual_result')
        ->selectRaw('reg_number, subjects, class_arm, term, session, COUNT(*) as cnt')
        ->groupBy('reg_number', 'subjects', 'class_arm', 'term', 'session')
        ->having('cnt', '>', 1)
        ->get()
        ->count();

    $this->info("Would collapse $dupCount duplicate groups (not executed). Use application aggregation or a custom migration after verification.");
})->purpose('Placeholder: preview duplicate groups before any future DB collapse');

Artisan::command('results:explain-annual-filter {reg_number} {class_arm} {term} {session}', function (string $reg_number, string $class_arm, string $term, string $session): void {
    $base = AnnualResult::query()
        ->where('reg_number', $reg_number)
        ->where('class_arm', $class_arm)
        ->where('term', $term)
        ->where('session', $session);

    $aggregated = AnnualResultAggregation::applyAggregatedSubjectScores($base);
    $sql = $aggregated->toSql();
    $bindings = $aggregated->getBindings();

    try {
        $plan = DB::select('EXPLAIN '.$sql, $bindings);
    } catch (Throwable $e) {
        $this->error($e->getMessage());

        return;
    }

    $this->info('EXPLAIN aggregated annual_result (report-card style filter + GROUP BY):');
    foreach ($plan as $row) {
        $this->line(json_encode($row, JSON_UNESCAPED_SLASHES));
    }
})->purpose('Run EXPLAIN on the aggregated annual_result query used for student report segments');
