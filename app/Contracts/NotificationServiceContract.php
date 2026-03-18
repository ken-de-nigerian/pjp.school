<?php

declare(strict_types=1);

namespace App\Contracts;

interface NotificationServiceContract
{
    public function add(string $title, string $message): void;
}
