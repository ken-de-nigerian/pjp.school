<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;

class NotificationPolicy
{
    public function viewAny(Admin $user): bool
    {
        return true; // Notifications visible to any logged-in admin
    }
}
