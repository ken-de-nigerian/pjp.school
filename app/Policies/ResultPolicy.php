<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Teacher;

class ResultPolicy
{
    public function publish(Admin|Teacher $user): bool
    {
        return $user instanceof Admin || $user instanceof Teacher;
    }

    public function upload(Admin|Teacher $user): bool
    {
        return true;
    }

    public function edit(Admin|Teacher $user): bool
    {
        return true;
    }

    public function approve(Admin|Teacher $user): bool
    {
        return $user instanceof Admin;
    }
}
