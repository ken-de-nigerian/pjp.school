<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ResultRemarkServiceContract;
use App\DTO\StoreResultRemarkDTO;
use App\Models\Position;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

final class ResultRemarkService implements ResultRemarkServiceContract
{
    /**
     * @throws Throwable
     */
    public function storeOrUpdate(StoreResultRemarkDTO $dto): void
    {
        $remark = $dto->remark;
        $remark = $remark !== null && $remark !== '' ? $remark : null;

        DB::transaction(function () use ($dto, $remark): void {
            $query = Position::query()
                ->where('reg_number', $dto->regNumber)
                ->where('class', $dto->class)
                ->where('term', $dto->term)
                ->where('session', $dto->session)
                ->lockForUpdate();

            $position = $query->first();

            if ($position !== null) {
                $position->update(['remark' => $remark]);

                return;
            }

            $student = Student::query()
                ->where('reg_number', $dto->regNumber)
                ->first();

            if ($student === null) {
                throw new RuntimeException('Student not found for this registration number.');
            }

            $name = trim(implode(' ', array_filter([
                (string) ($student->firstname ?? ''),
                (string) ($student->lastname ?? ''),
                (string) ($student->othername ?? ''),
            ])));

            if ($name === '') {
                $name = $dto->regNumber;
            }

            Position::query()->create([
                'reg_number' => $dto->regNumber,
                'name' => $name,
                'class' => $dto->class,
                'term' => $dto->term,
                'session' => $dto->session,
                'students_sub_total' => 0,
                'students_sub_average' => 0,
                'class_position' => 0,
                'date_added' => now()->format('Y-m-d H:i:s'),
                'status' => 0,
                'remark' => $remark,
            ]);
        });
    }
}
