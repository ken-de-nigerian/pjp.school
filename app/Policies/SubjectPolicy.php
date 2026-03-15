<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Subject;

class SubjectPolicy
{
    public function viewAny(Admin $user): bool
    {
        return true;
    }

    public function view(Admin $user, Subject $subject): bool
    {
        return true;
    }

    public function create(Admin $user): bool
    {
        return true;
    }

    public function update(Admin $user, Subject $subject): bool
    {
        return true;
    }

    public function delete(Admin $user, Subject $subject): bool
    {
        return true;
    }
}
