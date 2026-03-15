<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;

class StudentPolicy
{
    public function update(Admin|Teacher $user, Student $student): bool
    {
        return $user instanceof Admin || $user instanceof Teacher;
    }

    public function delete(Admin|Teacher $user, Student $student): bool
    {
        return $user instanceof Admin;
    }

    public function viewAny(Admin|Teacher $user): bool
    {
        return true;
    }

    public function view(Admin|Teacher $user, Student $student): bool
    {
        return true;
    }

    public function create(Admin|Teacher $user): bool
    {
        return $user instanceof Admin;
    }
}
