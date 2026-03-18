<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\NotificationServiceContract;
use App\Models\Notification;

final class NotificationService implements NotificationServiceContract
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
