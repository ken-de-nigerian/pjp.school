<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\News;

class NewsPolicy
{
    public function viewAny(Admin $user): bool
    {
        return true;
    }

    public function view(Admin $user, News $news): bool
    {
        return true;
    }

    public function create(Admin $user): bool
    {
        return true;
    }

    public function update(Admin $user, News $news): bool
    {
        return true;
    }

    public function delete(Admin $user, News $news): bool
    {
        return true;
    }
}
