<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Role;

class RolePolicy
{
    public function viewAny(Admin $user): bool
    {
        return true;
    }

    public function view(Admin $user, Role $role): bool
    {
        return true;
    }

    public function create(Admin $user): bool
    {
        return true;
    }

    public function update(Admin $user, Role $role): bool
    {
        return true;
    }

    public function delete(Admin $user, Role $role): bool
    {
        return true;
    }
}
