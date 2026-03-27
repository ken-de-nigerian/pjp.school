<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;

final class StudentPolicy
{
    public function update(Admin|Teacher $user, Student $_student): bool
    {
        if ($user instanceof Teacher) {
            return true;
        }

        return $user->hasPermission('manage_students');
    }

    public function delete(Admin|Teacher $user, Student $_student): bool
    {
        if ($user instanceof Teacher) {
            return false;
        }

        return $user->hasPermission('manage_students');
    }

    public function viewAny(Admin|Teacher $user): bool
    {
        if ($user instanceof Teacher) {
            return true;
        }

        return $user->hasPermission('manage_students');
    }

    public function view(Admin|Teacher $user, Student $_student): bool
    {
        if ($user instanceof Teacher) {
            return true;
        }

        return $user->hasPermission('manage_students');
    }

    public function create(Admin|Teacher $user): bool
    {
        if ($user instanceof Teacher) {
            return false;
        }

        return $user->hasPermission('manage_students');
    }
}
