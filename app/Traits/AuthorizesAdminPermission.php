<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait AuthorizesAdminPermission
{
    /**
     * Authorize that the authenticated admin has the given role permission.
     * Aborts with 403 if not.
     */
    protected function authorizePermission(string $key): void
    {
        $admin = Auth::guard('admin')->user();

        if ($admin === null || ! $admin->hasPermission($key)) {
            abort(403, 'You do not have permission to perform this action.');
        }
    }
}
