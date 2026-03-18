<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\News;

class NewsPolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->hasPermission('news');
    }

    public function view(Admin $user, News $news): bool
    {
        return $user->hasPermission('news');
    }

    public function create(Admin $user): bool
    {
        return $user->hasPermission('news');
    }

    public function update(Admin $user, News $news): bool
    {
        return $user->hasPermission('news');
    }

    public function delete(Admin $user, News $news): bool
    {
        return $user->hasPermission('news');
    }
}
