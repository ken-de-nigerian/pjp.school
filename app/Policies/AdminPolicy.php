<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;

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
        return $user->hasPermission('manage_staffs');
    }

    public function view(Admin $user, Admin $target): bool
    {
        return $user->hasPermission('manage_staffs');
    }

    public function create(Admin $user): bool
    {
        return $user->hasPermission('manage_staffs');
    }

    /**
     * Super admin (user_type 1) and admins with manage_staffs can edit staff.
     */
    public function update(Admin $user, Admin $target): bool
    {
        if ((int) $user->user_type === 1) {
            return true;
        }

        return $user->hasPermission('manage_staffs');
    }
}
