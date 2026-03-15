<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Notification;

class NotificationPolicy
{
    public function viewAny(Admin $user): bool
    {
        return true;
    }
}
