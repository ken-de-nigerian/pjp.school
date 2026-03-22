<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    public $timestamps = false;

    protected $table = 'positions';

    protected $fillable = [
        'reg_number',
        'name',
        'class',
        'term',
        'session',
        'students_sub_total',
        'students_sub_average',
        'class_position',
        'date_added',
        'status',
        'remark',
    ];

    protected function casts(): array
    {
        return [
            'date_added' => 'datetime',
        ];
    }

    public function scopeForClassTermSession(Builder $query, string $class, string $term, string $session): void
    {
        $query->where('class', $class)
            ->where('term', $term)
            ->where('session', $session);
    }

    /**
     * Custom principal remark when a set; otherwise a universal remark derived from {@see $students_sub_average}.
     */
    public function resolvedPrincipalRemark(): string
    {
        $custom = $this->remark;
        if (is_string($custom) && trim($custom) !== '') {
            return trim($custom);
        }

        $average = $this->students_sub_average;

        return $this->universalRemarkForAverage(is_numeric($average) ? (float) $average : 0.0);
    }

    private function universalRemarkForAverage(float $average): string
    {
        if ($average >= 90 && $average <= 100) {
            return 'An excellent average has been achieved. Maintain this strong performance and continue to improve. Kudos.';
        }
        if ($average >= 80 && $average < 90) {
            return 'An excellent result has been attained. Maintain this momentum and remain focused on the top';
        }
        if ($average >= 70 && $average < 80) {
            return 'Very good academic performance. Maintain consistency and strive for further improvement';
        }
        if ($average >= 60 && $average < 70) {
            return 'A good average has been recorded. Avoid complacency and continue striving for improvement.';
        }
        if ($average >= 55 && $average < 60) {
            return 'Fair average. Target an average above 65 and above next term. Take your continuous assessment seriously.';
        }
        if ($average >= 50 && $average < 55) {
            return 'Your performance is not satisfactory. You need to work harder and take continuous assessment more seriously';
        }
        if ($average >= 40 && $average < 50) {
            return 'I trust you can do better than this. Lack of seriousness toward your studies is affecting your academic performance. Take your continuous assessment more seriously';
        }
        if ($average >= 35 && $average < 40) {
            return 'Fail! There should be no room for failure! Please You need to put in serious effort. Prioritize your education now.';
        }
        if ($average >= 0 && $average < 35) {
            return 'Fail. You need to put in significantly more effort to meet the academic standards of the school.';
        }

        if ($average > 100) {
            return 'An excellent average has been achieved. Maintain this strong performance and continue to improve. Kudos.';
        }

        return 'Fail. You need to put in significantly more effort to meet the academic standards of the school.';
    }
}
