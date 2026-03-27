<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Setting;

final class SettingPolicy
{
    public function update(Admin $user, ?Setting $_setting = null): bool
    {
        return $user->hasPermission('general_settings');
    }
}
