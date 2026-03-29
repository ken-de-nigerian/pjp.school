<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Auth\Access\Response;

final class TeacherPolicy
{
    /*
    |--------------------------------------------------------------------------
    | Admin panel (TeachersController uses Gate on Teacher model)
    |--------------------------------------------------------------------------
    */

    public function viewAny(mixed $user): bool
    {
        return $user instanceof Admin;
    }

    public function update(mixed $user, Teacher $teacher): bool
    {
        return $user instanceof Admin;
    }

    public function delete(mixed $user, Teacher $teacher): bool
    {
        return $user instanceof Admin;
    }

    /*
    |--------------------------------------------------------------------------
    | Teacher portal (self, via authorizeForUser + teacher guard)
    |--------------------------------------------------------------------------
    */

    /**
     * Form teacher status unlocks attendance and behavioral tools (not result editing).
     */
    private function teacherIsFormTeacher(Teacher $teacher): bool
    {
        return (int) ($teacher->{'form-teacher'} ?? 0) === 1;
    }

    private function teacherMayModifyUploadedResults(Teacher $teacher): bool
    {
        return (int) ($teacher->modify_results ?? 0) === 1;
    }

    private function ownsTeacherRecord(Teacher $user, Teacher $teacher): bool
    {
        return $user->getAuthIdentifier() === $teacher->getAuthIdentifier();
    }

    /**
     * Dashboard / general “can use class-operational features.”
     */
    public function operateActiveTeacherFeatures(Teacher $user, Teacher $teacher): Response
    {
        if (! $this->ownsTeacherRecord($user, $teacher)) {
            return Response::deny('Unauthorized.');
        }

        return $this->teacherIsFormTeacher($teacher)
            ? Response::allow()
            : Response::deny('You do not have permission to use this feature.');
    }

    public function manageAttendance(Teacher $user, Teacher $teacher): Response
    {
        if (! $this->ownsTeacherRecord($user, $teacher)) {
            return Response::deny('Unauthorized.');
        }

        return $this->teacherIsFormTeacher($teacher)
            ? Response::allow()
            : Response::deny('You are not allowed to add attendance.');
    }

    public function manageBehavioral(Teacher $user, Teacher $teacher): Response
    {
        if (! $this->ownsTeacherRecord($user, $teacher)) {
            return Response::deny('Unauthorized.');
        }

        return $this->teacherIsFormTeacher($teacher)
            ? Response::allow()
            : Response::deny('You are not allowed to add behavioral analysis.');
    }

    /**
     * Only the "modify results" flag allows editing uploaded results (independent of form teacher).
     */
    public function modifyResults(Teacher $user, Teacher $teacher): Response
    {
        if (! $this->ownsTeacherRecord($user, $teacher)) {
            return Response::deny('Unauthorized.');
        }

        return $this->teacherMayModifyUploadedResults($teacher)
            ? Response::allow()
            : Response::deny('You are not allowed to modify results.');
    }
}
