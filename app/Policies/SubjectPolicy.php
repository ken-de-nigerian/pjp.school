<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Subject;

final class SubjectPolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->hasPermission('manage_subjects');
    }

    public function view(Admin $user, Subject $subject): bool
    {
        return $user->hasPermission('manage_subjects');
    }

    public function create(Admin $user): bool
    {
        return $user->hasPermission('manage_subjects');
    }

    public function update(Admin $user, Subject $subject): bool
    {
        return $user->hasPermission('manage_subjects');
    }

    public function delete(Admin $user, Subject $subject): bool
    {
        return $user->hasPermission('manage_subjects');
    }
}
