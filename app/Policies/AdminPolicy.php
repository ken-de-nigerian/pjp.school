<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Support\Coercion;

final class AdminPolicy
{
    /**
     * Only admins with user_type 1 (super admin) can delete other admins.
     */
    public function delete(Admin $user, Admin $target): bool
    {
        if (Coercion::int($user->id) === Coercion::int($target->id)) {
            return false;
        }

        return Coercion::int($user->user_type) === 1;
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
        if (Coercion::int($user->user_type) === 1) {
            return true;
        }

        return $user->hasPermission('manage_staffs');
    }
}
