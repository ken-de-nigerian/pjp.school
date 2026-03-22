<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\AnnualResult;
use Illuminate\Database\Eloquent\Builder;

final class AnnualResultAggregation
{
    private const T = 'annual_result';

    /**
     * Apply SELECT … GROUP BY for one logical subject row per student/context.
     *
     * @param  Builder<AnnualResult>  $query
     * @return Builder<AnnualResult>
     */
    public static function applyAggregatedSubjectScores(Builder $query): Builder
    {
        $t = self::T;

        return $query
            ->selectRaw(implode(',', [
                "MIN($t.id) as id",
                "MIN($t.studentId) as studentId",
                "MIN($t.class) as class",

                "$t.reg_number",
                "$t.class_arm",
                "$t.term",
                "$t.session",
                "$t.subjects",

                "MIN($t.name) as name",

                "ROUND(AVG($t.ca)) as ca",
                "ROUND(AVG($t.assignment)) as assignment",
                "ROUND(AVG($t.exam)) as exam",

                "ROUND(
                    ROUND(AVG($t.ca)) +
                    ROUND(AVG($t.assignment)) +
                    ROUND(AVG($t.exam))
                ) as total",

                self::aggregatedStatusExpression($t).' as status',

                "MAX($t.date_added) as date_added",
            ]))
            ->groupBy(
                "$t.reg_number",
                "$t.subjects",
                "$t.class_arm",
                "$t.term",
                "$t.session",
            );
    }

    /**
     * Pending (2) wins if any row is pending;
     * else approved (1) if any;
     * else rejected (3).
     */
    private static function aggregatedStatusExpression(string $table): string
    {
        return "CASE
            WHEN SUM(CASE WHEN $table.status = 2 THEN 1 ELSE 0 END) > 0 THEN 2
            WHEN SUM(CASE WHEN $table.status = 1 THEN 1 ELSE 0 END) > 0 THEN 1
            ELSE 3
        END";
    }
}
