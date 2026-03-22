<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Fee;

final class FeePolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->hasPermission('general_settings');
    }

    public function create(Admin $user): bool
    {
        return $user->hasPermission('general_settings');
    }

    public function update(Admin $user, Fee $fee): bool
    {
        return $user->hasPermission('general_settings');
    }

    public function delete(Admin $user, Fee $fee): bool
    {
        return $user->hasPermission('general_settings');
    }
}
