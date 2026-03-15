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

    public function getRecent(int $limit = 20): \Illuminate\Database\Eloquent\Collection
    {
        return Notification::query()
            ->orderByDesc('date_added')
            ->limit($limit)
            ->get();
    }

    public function getPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return Notification::query()
            ->orderByDesc('date_added')
            ->paginate($perPage);
    }
}
