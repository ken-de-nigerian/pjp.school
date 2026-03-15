<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AnnualResult;
use App\Models\Behavioral;
use App\Models\Position;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Support\Collection;

/**
 * Public result check: replicates legacy Result::index flow (hasStudentID, getStudentFees,
 * hasPublished, pin validation, StudentReport, ReportCard, psychomotor, getSegment).
 */
class ResultCheckService
{
    private const MAX_PIN_USES = 15;

    public function __construct(
        private PinService $pinService,
        private ResultService $resultService
    ) {}

    public function getSettings(): array
    {
        return Setting::getCached();
    }

    /** Legacy: getSessions — list for dropdown (year). */
    public function getSessions(): array
    {
        return \App\Models\AcademicSession::query()->orderByDesc('year')->get()->toArray();
    }

    /** Legacy: hasStudentID — student exists, class not Left/Graduated */
    public function hasStudentId(string $regNumber): bool
    {
        return Student::query()
            ->where('reg_number', $regNumber)
            ->notLeftOrGraduated()
            ->exists();
    }

    /** Get student for fee check and report; null if not found or Left/Graduated */
    public function getStudent(string $regNumber): ?Student
    {
        return Student::query()
            ->where('reg_number', $regNumber)
            ->notLeftOrGraduated()
            ->first();
    }

    /** Legacy: fee_status 1 = approved */
    public function hasApprovedFees(string $regNumber): bool
    {
        $student = $this->getStudent($regNumber);
        return $student && (int) $student->fee_status === 1;
    }

    /** Legacy: hasPublished — positions exist for class/term/session */
    public function hasPublished(string $class, string $term, string $session): bool
    {
        return $this->resultService->hasPublishedResults($class, $term, $session);
    }

    /** Scratch card enabled when settings scratch_card == 1 */
    public function isScratchCardRequired(): bool
    {
        $s = $this->getSettings();
        return (int) ($s['scratch_card'] ?? 0) === 1;
    }

    /** Validate pin and record use; returns null on success, error message on failure */
    public function validateAndRecordPin(?string $pin, string $regNumber, string $class, string $term, string $session): ?string
    {
        if ($pin === null || $pin === '') {
            return 'Scratch card number is required.';
        }
        if (! $this->pinService->hasPin($pin)) {
            return 'You have entered an incorrect pin.';
        }
        $used = $this->pinService->usedPinData($pin);
        if ($used) {
            if ($used->reg_number !== $regNumber) {
                return 'This pin has already been used by another student.';
            }
            $count = (int) $used->used_count;
            if ($count >= self::MAX_PIN_USES) {
                return 'You have exhausted your card usage validity.';
            }
            $this->pinService->markUsedUpdate($pin, $regNumber, $count + 1, $class, $session);
        } else {
            $this->pinService->markUsedInsert($pin, $regNumber, 1, $class, $term, $session);
        }
        return null;
    }

    /** Legacy: StudentReport — single student row */
    public function getStudentReport(string $regNumber): ?Student
    {
        return $this->getStudent($regNumber);
    }

    /** Legacy: ReportCard — positions row for reg_number, class, term, session */
    public function getReportCard(string $regNumber, string $class, string $term, string $session): ?Position
    {
        return Position::query()
            ->where('reg_number', $regNumber)
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->first();
    }

    /** Legacy: psychomotor — behavioral rows for reg_number, term, session */
    public function getBehavioral(string $regNumber, string $term, string $session): Collection
    {
        return Behavioral::query()
            ->where('reg_number', $regNumber)
            ->where('term', $term)
            ->where('session', $session)
            ->orderBy('segment')
            ->get();
    }

    /** Legacy: getSegment — annual_result rows for reg_number, class, term, session */
    public function getSegment(string $regNumber, string $class, string $term, string $session): Collection
    {
        return AnnualResult::query()
            ->where('reg_number', $regNumber)
            ->where('class_arm', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->orderBy('subjects')
            ->get();
    }
}
