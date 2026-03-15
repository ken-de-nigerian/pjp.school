<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Role;

class AdminPolicy
{
    /**
     * Only admins with user_type 1 (super admin) can delete other admins.
     */
    public function delete(Admin $user, Admin $target): bool
    {
        if ($user->adminId === $target->adminId) {
            return false;
        }

        return (int) $user->user_type === 1;
    }

    public function viewAny(Admin $user): bool
    {
        return true;
    }

    public function view(Admin $user, Admin $target): bool
    {
        return true;
    }

    public function create(Admin $user): bool
    {
        return true;
    }

    /**
     * Super admin (user_type 1) and Principal role can edit staff.
     */
    public function update(Admin $user, Admin $target): bool
    {
        if ((int) $user->user_type === 1) {
            return true;
        }
        $role = $user->relationLoaded('role') ? $user->role : Role::find($user->user_type);

        return $role && $role->name === 'Principal';
    }
}
