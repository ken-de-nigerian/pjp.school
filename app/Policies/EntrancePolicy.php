<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Entrance;

final class EntrancePolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->hasPermission('online_entrance');
    }

    public function view(Admin $user, Entrance $entrance): bool
    {
        return $user->hasPermission('online_entrance');
    }
}
