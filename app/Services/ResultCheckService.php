<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ResultServiceContract;
use App\Enums\ResultStatus;
use App\Models\AnnualResult;
use App\Models\Behavioral;
use App\Models\Position;
use App\Models\Setting;
use App\Models\Student;
use App\Support\AnnualResultAggregation;
use Illuminate\Support\Collection;
use Throwable;

final class ResultCheckService
{
    private const MAX_PIN_USES = 15;

    public function __construct(
        private readonly PinService $pinService,
        private readonly ResultServiceContract $resultService
    ) {}

    /** @return array<string, mixed> */
    public function getSettings(): array
    {
        return Setting::getCached();
    }

    public function hasStudentId(string $regNumber): bool
    {
        return Student::query()
            ->where('reg_number', $regNumber)
            ->notLeftOrGraduated()
            ->exists();
    }

    public function getStudent(string $regNumber): ?Student
    {
        return Student::query()
            ->where('reg_number', $regNumber)
            ->notLeftOrGraduated()
            ->first();
    }

    public function hasApprovedFees(string $regNumber): bool
    {
        $student = $this->getStudent($regNumber);

        return $student && (int) $student->fee_status === 1;
    }

    public function hasPublished(string $class, string $term, string $session): bool
    {
        return $this->resultService->hasPublishedResults($class, $term, $session);
    }

    public function isScratchCardRequired(): bool
    {
        $s = $this->getSettings();

        return (int) ($s['scratch_card'] ?? 0) === 1;
    }

    /**
     * @throws Throwable
     */
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

    public function getStudentReport(string $regNumber): ?Student
    {
        return $this->getStudent($regNumber);
    }

    public function getReportCard(string $regNumber, string $class, string $term, string $session): ?Position
    {
        return Position::query()
            ->where('reg_number', $regNumber)
            ->where('class', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->first();
    }

    /** @return Collection<int, Behavioral> */
    public function getBehavioral(string $regNumber, string $term, string $session): Collection
    {
        return Behavioral::query()
            ->where('reg_number', $regNumber)
            ->where('term', $term)
            ->where('session', $session)
            ->orderBy('id')
            ->get();
    }

    /** @return Collection<int, mixed> */
    public function getSegment(string $regNumber, string $class, string $term, string $session): Collection
    {
        $base = AnnualResult::query()
            ->where('reg_number', $regNumber)
            ->where('class_arm', $class)
            ->where('term', $term)
            ->where('session', $session)
            ->where('status', ResultStatus::APPROVED->value);

        return AnnualResultAggregation::applyAggregatedSubjectScores($base)
            ->orderBy('subjects')
            ->get();
    }

    public function getClassCount(string $class): int
    {
        return Student::query()
            ->where('class_arm', $class)
            ->whereNotIn('class', ['Left', 'Graduated'])
            ->count();
    }
}
