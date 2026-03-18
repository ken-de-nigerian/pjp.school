<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\NotificationServiceContract;

final readonly class SendNotificationAction
{
    public function __construct(
        private NotificationServiceContract $notificationService
    ) {}

    public function execute(string $title, string $message): void
    {
        $this->notificationService->add($title, $message);
    }
}
