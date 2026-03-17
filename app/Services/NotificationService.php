<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationService
{
    public function add(string $title, string $message): void
    {
        Notification::query()->create([
            'title' => $title,
            'message' => $message,
            'date_added' => now()->format('Y-m-d H:i:s'),
        ]);
    }
}
