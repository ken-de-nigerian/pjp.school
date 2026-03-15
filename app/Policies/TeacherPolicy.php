<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Teacher;

class TeacherPolicy
{
    public function viewAny(Admin $user): bool
    {
        return true;
    }

    public function view(Admin $user, Teacher $teacher): bool
    {
        return true;
    }

    public function update(Admin $user, Teacher $teacher): bool
    {
        return true;
    }

    public function delete(Admin $user, Teacher $teacher): bool
    {
        return true;
    }
}
