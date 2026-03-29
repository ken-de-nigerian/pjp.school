<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\ClassArm;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Support\Coercion;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class StudentService
{
    /**
     * @return array<int, array{id: mixed, class_name: mixed, time_added: mixed, user_count: int}>
     */
    public function getClassesWithCounts(): array
    {
        return Cache::remember('student_class_counts', 300, function () {
            $countByClass = [];
            Student::query()
                ->active()
                ->get(['class'])
                ->each(function (Student $row) use (&$countByClass): void {
                    foreach (array_filter(array_map('trim', explode(',', Coercion::string($row->class ?? '')))) as $c) {
                        $countByClass[$c] = ($countByClass[$c] ?? 0) + 1;
                    }
                });

            return SchoolClass::query()
                ->orderBy('class_name')
                ->get()
                ->map(fn ($class) => [
                    'id' => $class->id,
                    'class_name' => $class->class_name,
                    'time_added' => $class->time_added,
                    'user_count' => $countByClass[$class->class_name] ?? 0,
                ])
                ->values()
                ->all();
        });
    }

    /** @return list<array{house: string, user_count: int}> */
    public function getHouseCounts(): array
    {
        $housesRaw = config('school.houses', []);
        $houses = [];
        if (is_array($housesRaw)) {
            foreach ($housesRaw as $h) {
                if (is_string($h) && $h !== '') {
                    $houses[] = $h;
                }
            }
        }

        if ($houses === []) {
            return [];
        }

        $counts = Student::query()
            ->active()
            ->notLeftOrGraduated()
            ->selectRaw('house, COUNT(*) as user_count')
            ->whereIn('house', $houses)
            ->groupBy('house')
            ->pluck('user_count', 'house')
            ->toArray();

        $results = [];
        foreach ($houses as $house) {
            $results[] = [
                'house' => $house,
                'user_count' => Coercion::int($counts[$house] ?? 0),
            ];
        }

        return $results;
    }

    /** @return LengthAwarePaginator<int, Student> */
    public function getStudentsInHouse(string $house, ?string $search = null, ?string $class = null, int $perPage = 25): LengthAwarePaginator
    {
        $query = Student::query()
            ->active()
            ->notLeftOrGraduated()
            ->byHouse($house)
            ->orderBy('firstname')
            ->orderBy('lastname');

        if ($search !== null && $search !== '') {
            $term = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function ($q) use ($term) {
                $q->where('firstname', 'like', $term)
                    ->orWhere('lastname', 'like', $term)
                    ->orWhere('othername', 'like', $term)
                    ->orWhere('reg_number', 'like', $term);
            });
        }
        if ($class !== null && $class !== '') {
            $query->byClass($class);
        }

        return $query->paginate($perPage)->appends(request()->query());
    }

    /** @return LengthAwarePaginator<int, Student> */
    public function getStudentsByClass(string $class, int $perPage = 25): LengthAwarePaginator
    {
        return Student::query()
            ->active()
            ->byClass($class)
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->paginate($perPage);
    }

    /** @return Collection<int, Student> */
    public function getStudentsByClassAll(string $class): Collection
    {
        return Student::query()
            ->active()
            ->byClass($class)
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get();
    }

    /** @return Collection<int, Student> */
    public function getStudentsByClassAndSubject(?string $class, ?string $subject): Collection
    {
        $query = Student::query()->active()->orderBy('firstname')->orderBy('lastname');
        if ($class !== null && $class !== '') {
            $query->byClass($class);
        }
        $students = $query->get();

        if ($subject !== null && $subject !== '') {
            $subjectNorm = strtolower(trim($subject));
            $students = $students->filter(function ($student) use ($subjectNorm) {
                $registered = array_map('trim', explode(',', Coercion::string($student->subjects ?? '')));
                foreach ($registered as $s) {
                    if ($s !== '' && strtolower($s) === $subjectNorm) {
                        return true;
                    }
                }

                return false;
            });
        }

        return $students->values();
    }

    public function registerStudentSubjects(string $studentListRegNumber, string $subjectsList): int
    {
        return Student::query()
            ->where('reg_number', $studentListRegNumber)
            ->update(['subjects' => $subjectsList]);
    }

    /** @return list<string> */
    public function getGraduationDates(): array
    {
        $dates = Student::query()
            ->whereNotNull('graduation_date')
            ->distinct()
            ->pluck('graduation_date')
            ->map(static function (mixed $d): ?string {
                if ($d instanceof DateTimeInterface) {
                    return $d->format('Y');
                }
                if (is_string($d)) {
                    return substr($d, 0, 4);
                }

                return null;
            })
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();

        return array_values($dates);
    }

    /** @return list<array{year: string, user_count: int}> */
    public function getGraduationYearsWithCounts(): array
    {
        $years = $this->getGraduationDates();
        if (empty($years)) {
            return [];
        }

        $results = [];
        foreach ($years as $year) {
            $count = Student::query()
                ->whereNotNull('graduation_date')
                ->where('graduation_date', 'like', $year.'%')
                ->count();
            $results[] = [
                'year' => $year,
                'user_count' => $count,
            ];
        }

        return $results;
    }

    /** @return Collection<int, Student> */
    public function getStudentsByGraduationYear(string $year): Collection
    {
        return Student::query()
            ->whereNotNull('graduation_date')
            ->where('graduation_date', 'like', $year.'%')
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get();
    }

    /** @return list<string> */
    public function getLeftSchoolDates(): array
    {
        return $this->sortLeftSchoolYearKeys(array_keys($this->leftSchoolYearBuckets()));
    }

    /** @return list<array{year: string, user_count: int}> */
    public function getLeftSchoolYearsWithCounts(): array
    {
        $buckets = $this->leftSchoolYearBuckets();
        if ($buckets === []) {
            return [];
        }

        $results = [];
        foreach ($this->sortLeftSchoolYearKeys(array_keys($buckets)) as $year) {
            $results[] = [
                'year' => $year,
                'user_count' => Coercion::int($buckets[$year] ?? 0),
            ];
        }

        return $results;
    }

    /**
     * @return array<string, int>
     */
    private function leftSchoolYearBuckets(): array
    {
        $byYear = [];
        foreach ($this->leftSchoolCandidatesQuery()->get() as $student) {
            $key = $this->leftSchoolYearKey($student);
            if ($key === null) {
                continue;
            }
            $byYear[$key] = ($byYear[$key] ?? 0) + 1;
        }

        return $byYear;
    }

    /**
     * @param list<string> $years
     *
     * @return list<string>
     */
    private function sortLeftSchoolYearKeys(array $years): array
    {
        if ($years === []) {
            return [];
        }

        usort($years, function (string $a, string $b): int {
            if ($a === Student::LEFT_SCHOOL_UNDATED_YEAR) {
                return 1;
            }
            if ($b === Student::LEFT_SCHOOL_UNDATED_YEAR) {
                return -1;
            }

            return strcmp($a, $b);
        });

        return $years;
    }

    /** @return Collection<int, Student> */
    public function getStudentsWhoLeftSchool(string $year): Collection
    {
        return $this->leftSchoolCandidatesQuery()
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get()
            ->filter(fn (Student $s): bool => $this->leftSchoolYearKey($s) === $year)
            ->values();
    }

    /**
     * Students shown under Admin → Left school: status + leave date, or `class` indicates they left (Left, left-school, …).
     *
     * @return Builder<Student>
     */
    private function leftSchoolCandidatesQuery(): Builder
    {
        return Student::query()
            ->where(function (Builder $q): void {
                $q->where(function (Builder $q2): void {
                    $q2->where('status', 1)->whereNotNull('left_school_date');
                })->orWhere(function (Builder $q3): void {
                    $q3->classIndicatesLeftSchool();
                });
            })
            ->excludeGraduatedClass();
    }

    private function studentQualifiesForLeftSchoolListing(Student $student): bool
    {
        if ($this->normalizedClassValue($student) === 'graduated') {
            return false;
        }
        if (Coercion::int($student->status ?? 0) === 1 && $student->left_school_date !== null) {
            return true;
        }

        return $this->studentClassIndicatesLeftSchool($student);
    }

    private function studentClassIndicatesLeftSchool(Student $student): bool
    {
        $c = $this->normalizedClassValue($student);

        return in_array($c, ['left', 'left-school', 'left school'], true);
    }

    private function normalizedClassValue(Student $student): string
    {
        return strtolower(trim(Coercion::string($student->class ?? '')));
    }

    private function leftSchoolYearKey(Student $student): ?string
    {
        if (! $this->studentQualifiesForLeftSchoolListing($student)) {
            return null;
        }
        if ($student->left_school_date !== null) {
            return $this->extractYearFromDateish($student->left_school_date);
        }
        if ($student->time_of_reg !== null) {
            return $this->extractYearFromDateish($student->time_of_reg);
        }

        return Student::LEFT_SCHOOL_UNDATED_YEAR;
    }

    private function extractYearFromDateish(mixed $value): string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y');
        }
        if (is_string($value) && strlen($value) >= 4) {
            return substr($value, 0, 4);
        }

        return Student::LEFT_SCHOOL_UNDATED_YEAR;
    }

    public function getById(int $id): ?Student
    {
        $student = Student::query()->find($id);

        return $student instanceof Student ? $student : null;
    }

    public function getByRegNumber(string $regNumber): ?Student
    {
        return Student::query()->active()->where('reg_number', $regNumber)->first();
    }

    /** @return LengthAwarePaginator<int, Student> */
    public function getStudentsByClassWithSearch(string $class, string $search, int $perPage = 25): LengthAwarePaginator
    {
        $query = Student::query()
            ->active()
            ->byClass($class)
            ->orderBy('firstname')
            ->orderBy('lastname');

        if ($search !== '') {
            $term = '%'.$search.'%';
            $query->where(function ($q) use ($term) {
                $q->where('reg_number', 'like', $term)
                    ->orWhere('firstname', 'like', $term)
                    ->orWhere('lastname', 'like', $term)
                    ->orWhere('othername', 'like', $term);
            });
        }

        return $query->paginate($perPage);
    }

    /** @return array<int, SchoolClass> */
    public function getClassesArray(): array
    {
        return SchoolClass::query()->orderBy('class_name')->get()->values()->all();
    }

    public function getNextRegNumber(): int
    {
        $maxReg = Student::query()
            ->selectRaw('MAX(CAST(reg_number AS UNSIGNED)) as max_reg')
            ->value('max_reg');

        return Coercion::int($maxReg) + 1;
    }

    /** @return Collection<int, Subject> */
    public function getSubjectsToRegister(string $selectedClass): Collection
    {
        $classArm = substr($selectedClass, 0, 3);
        if ($classArm === 'JSS') {
            return Subject::query()->where('grade', 'Junior')->orderBy('subject_name')->get();
        }
        if ($classArm === 'SSS') {
            return Subject::query()->where('grade', 'Senior')->orderBy('subject_name')->get();
        }

        return Subject::query()->whereRaw('0 = 1')->get();
    }

    public function hasClass(string $class_name): bool
    {
        return SchoolClass::query()->where('class_name', $class_name)->exists();
    }

    public function addClass(string $class_name): int
    {
        SchoolClass::query()->create([
            'class_name' => $class_name,
            'time_added' => now()->format('Y-m-d H:i:s'),
        ]);

        return 1;
    }

    public function updateClass(int $id, string $newName): bool
    {
        return (bool) SchoolClass::query()
            ->where('id', $id)
            ->update([
                'class_name' => $newName,
            ]);
    }

    public function classHasStudents(string $class_name): bool
    {
        return Student::query()
            ->active()
            ->byClass($class_name)
            ->exists();
    }

    public function deleteClassIfEmpty(int $id, string $class_name): bool
    {
        if ($this->classHasStudents($class_name)) {
            return false;
        }

        return (bool) SchoolClass::query()->where('id', $id)->delete();
    }

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes, ?string $imagelocation = null): Student
    {
        $classArm = ClassArm::fromClass(Coercion::string($attributes['class'] ?? ''));
        $data = array_merge($attributes, [
            'class_arm' => $classArm,
            'imagelocation' => $imagelocation ?? 'default.png',
            'time_of_reg' => now()->format('Y-m-d H:i:s'),
        ]);
        if (! isset($data['status'])) {
            $data['status'] = 2;
        }

        return Student::query()->create($data);
    }

    /** @param array<string, mixed> $data */
    public function updateAccount(int $id, array $data): int
    {
        return Student::query()->where('id', $id)->update([
            'firstname' => $data['firstname'] ?? null,
            'lastname' => $data['lastname'] ?? null,
            'othername' => $data['othername'] ?? null,
            'dob' => $data['dob'] ?? null,
            'gender' => $data['gender'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null,
        ]);
    }

    public function updateAcademicProfile(int $id, string $class, string $subjects, string $reg_number): int
    {
        $classArm = ClassArm::fromClass($class);

        return Student::query()->where('id', $id)->update([
            'class' => $class,
            'class_arm' => $classArm,
            'subjects' => $subjects,
            'reg_number' => $reg_number,
        ]);
    }

    /** @param array<string, mixed> $data */
    public function updateContactAddress(int $id, array $data): int
    {
        return Student::query()->where('id', $id)->update([
            'lga' => $data['lga'] ?? null,
            'state' => $data['state'] ?? null,
            'city' => $data['city'] ?? null,
            'nationality' => $data['nationality'] ?? null,
            'address' => $data['address'] ?? null,
        ]);
    }

    /** @param array<string, mixed> $data */
    public function updateParentsInformation(int $id, array $data): int
    {
        return Student::query()->where('id', $id)->update([
            'father_name' => $data['father_name'] ?? null,
            'father_occupation' => $data['father_occupation'] ?? null,
            'father_phone' => $data['father_phone'] ?? null,
            'mother_name' => $data['mother_name'] ?? null,
            'mother_occupation' => $data['mother_occupation'] ?? null,
            'mother_phone' => $data['mother_phone'] ?? null,
        ]);
    }

    /** @param array<string, mixed> $data */
    public function updateSponsorsInformation(int $id, array $data): int
    {
        return Student::query()->where('id', $id)->update([
            'sponsor_name' => $data['sponsor_name'] ?? null,
            'sponsor_occupation' => $data['sponsor_occupation'] ?? null,
            'sponsor_phone' => $data['sponsor_phone'] ?? null,
            'sponsor_address' => $data['sponsor_address'] ?? null,
            'relationship' => $data['relationship'] ?? null,
        ]);
    }

    public function updateOtherInformation(int $id, string $house, string $category): int
    {
        return Student::query()->where('id', $id)->update([
            'house' => $house,
            'category' => $category,
        ]);
    }

    public function toggleStatus(int $id, int $status, string $class_arm): int
    {
        return Student::query()->where('id', $id)->update([
            'status' => $status,
            'class_arm' => $class_arm,
            'left_school_date' => $status === 1 ? now()->format('Y-m-d H:i:s') : null,
        ]);
    }

    public function toggleFeeStatus(int $id, int $fee_status): int
    {
        return Student::query()->where('id', $id)->update(['fee_status' => $fee_status]);
    }

    /** @param array<int, int> $ids */
    public function updateFeeStatusBulk(array $ids, int $fee_status): int
    {
        if (count($ids) === 0) {
            return 0;
        }
        $ids = array_values(array_unique(array_map('intval', $ids)));

        return Student::query()->whereIn('id', $ids)->update(['fee_status' => $fee_status]);
    }

    /**
     * Promote selected students from one class to another.
     *
     * @param  list<int>  $studentIds
     */
    public function promote(string $fromClass, string $toClass, array $studentIds): bool
    {
        $data = ['class' => $toClass, 'class_arm' => ClassArm::fromClass($toClass)];
        if ($toClass === 'Graduated') {
            $data['graduation_date'] = now()->format('Y-m-d H:i:s');
        }

        return Student::query()
            ->where('class', $fromClass)
            ->whereIn('id', array_values(array_unique(array_map('intval', $studentIds))))
            ->update($data) !== 0;
    }

    /** @param list<int> $studentIds */
    public function demote(string $toClass, array $studentIds): bool
    {
        $data = ['class' => $toClass, 'class_arm' => ClassArm::fromClass($toClass)];
        if ($toClass === 'Graduated') {
            $data['graduation_date'] = now()->format('Y-m-d H:i:s');
        }

        return Student::query()
            ->whereIn('id', array_values(array_unique(array_map('intval', $studentIds))))
            ->update($data) !== 0;
    }

    public function delete(int $id): bool
    {
        return (bool) Student::query()->where('id', $id)->delete();
    }

    public function updateProfilePicture(int $studentId, string $path): bool
    {
        return Student::query()->where('id', $studentId)->update(['imagelocation' => $path]) !== 0;
    }
}
