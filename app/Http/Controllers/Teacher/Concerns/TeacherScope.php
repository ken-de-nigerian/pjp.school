<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher\Concerns;

use App\Models\Teacher;
use Illuminate\Support\Facades\Gate;

trait TeacherScope
{
    /** @return array<int, string> */
    protected function teacherAssignedClasses(): array
    {
        $teacher = auth('teacher')->user();
        $raw = is_object($teacher) ? (string) ($teacher->assigned_class ?? '') : '';
        $parts = array_values(array_filter(array_map('trim', explode(',', $raw))));

        return array_values(array_unique($parts));
    }

    /** @return array<int, string> */
    protected function teacherSubjects(): array
    {
        $teacher = auth('teacher')->user();
        $raw = is_object($teacher) ? (string) ($teacher->subject_to_teach ?? '') : '';
        $parts = array_values(array_filter(array_map('trim', explode(',', $raw))));

        return array_values(array_unique($parts));
    }

    protected function ensureTeacherCanAccessClass(string $class): void
    {
        if ($class === '' || ! in_array($class, $this->teacherAssignedClasses(), true)) {
            abort(403, 'Unauthorized.');
        }
    }

    protected function ensureTeacherCanAccessSubject(string $subject): void
    {
        if ($subject === '' || ! in_array($subject, $this->teacherSubjects(), true)) {
            abort(403, 'Unauthorized.');
        }
    }

    /**
     * Authorize against TeacherPolicy using the teacher guard user (default app guard is not "teacher").
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function authorizeTeacherAbility(string $ability): void
    {
        $teacher = auth('teacher')->user();
        abort_unless($teacher instanceof Teacher, 401);

        $this->authorizeForUser($teacher, $ability, $teacher);
    }

    protected function teacherPolicyAllows(string $ability): bool
    {
        $teacher = auth('teacher')->user();
        if (! $teacher instanceof Teacher) {
            return false;
        }

        return Gate::forUser($teacher)->allows($ability, $teacher);
    }
}
