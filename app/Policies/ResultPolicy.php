<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Teacher;

final class ResultPolicy
{
    public function publish(Admin|Teacher $user): bool
    {
        if ($user instanceof Teacher) {
            return true;
        }

        return $user->hasPermission('publish_result');
    }

    public function upload(Admin|Teacher $user): bool
    {
        if ($user instanceof Teacher) {
            return true;
        }

        return $user->hasPermission('upload_result');
    }

    public function edit(Admin|Teacher $user): bool
    {
        if ($user instanceof Teacher) {
            return true;
        }

        return $user->hasPermission('view_uploaded_results');
    }

    public function approve(Admin|Teacher $user): bool
    {
        return $user instanceof Admin && $user->hasPermission('view_uploaded_results');
    }
}
