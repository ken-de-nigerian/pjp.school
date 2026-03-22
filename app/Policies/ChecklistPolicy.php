<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Checklist;

final class ChecklistPolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->hasPermission('general_settings');
    }

    public function create(Admin $user): bool
    {
        return $user->hasPermission('general_settings');
    }

    public function update(Admin $user, Checklist $checklist): bool
    {
        return $user->hasPermission('general_settings');
    }

    public function delete(Admin $user, Checklist $checklist): bool
    {
        return $user->hasPermission('general_settings');
    }
}
