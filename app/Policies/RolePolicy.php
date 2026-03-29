<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Role;

final class RolePolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->hasPermission('general_settings');
    }

    public function view(Admin $user, Role $role): bool
    {
        return $user->hasPermission('general_settings');
    }

    public function create(Admin $user): bool
    {
        return $user->hasPermission('general_settings');
    }

    public function update(Admin $user, Role $role): bool
    {
        return $user->hasPermission('general_settings');
    }

    public function delete(Admin $user, Role $role): bool
    {
        return $user->hasPermission('general_settings');
    }
}
