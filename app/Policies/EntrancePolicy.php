<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Entrance;

class EntrancePolicy
{
    public function viewAny(Admin $user): bool
    {
        return true;
    }

    public function view(Admin $user, Entrance $entrance): bool
    {
        return true;
    }
}
