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
     * Form teacher or modify-results flag unlocks attendance, behavioural, etc.
     */
    private function teacherHasActiveFeatureFlags(Teacher $teacher): bool
    {
        $modifyResults = (int) ($teacher->modify_results ?? 0);
        $formTeacher = (int) ($teacher->{'form-teacher'} ?? 0);

        return $modifyResults === 1 || $formTeacher === 1;
    }

    private function ownsTeacherRecord(Teacher $user, Teacher $teacher): bool
    {
        return $user->getAuthIdentifier() === $teacher->getAuthIdentifier();
    }

    /**
     * Dashboard / general “can use class-operational features”.
     */
    public function operateActiveTeacherFeatures(Teacher $user, Teacher $teacher): Response|bool
    {
        if (! $this->ownsTeacherRecord($user, $teacher)) {
            return Response::deny('Unauthorized.');
        }

        return $this->teacherHasActiveFeatureFlags($teacher)
            ? Response::allow()
            : Response::deny('You do not have permission to use this feature.');
    }

    public function manageAttendance(Teacher $user, Teacher $teacher): Response|bool
    {
        if (! $this->ownsTeacherRecord($user, $teacher)) {
            return Response::deny('Unauthorized.');
        }

        return $this->teacherHasActiveFeatureFlags($teacher)
            ? Response::allow()
            : Response::deny('You are not allowed to add attendance.');
    }

    public function manageBehavioral(Teacher $user, Teacher $teacher): Response|bool
    {
        if (! $this->ownsTeacherRecord($user, $teacher)) {
            return Response::deny('Unauthorized.');
        }

        return $this->teacherHasActiveFeatureFlags($teacher)
            ? Response::allow()
            : Response::deny('You are not allowed to add behavioral analysis.');
    }

    /**
     * Same flags as operational features; separate ability for clearer messaging on results.
     */
    public function modifyResults(Teacher $user, Teacher $teacher): Response|bool
    {
        if (! $this->ownsTeacherRecord($user, $teacher)) {
            return Response::deny('Unauthorized.');
        }

        return $this->teacherHasActiveFeatureFlags($teacher)
            ? Response::allow()
            : Response::deny('You are not allowed to modify results.');
    }
}
