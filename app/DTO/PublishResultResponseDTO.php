<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class PublishResultResponseDTO
{
    public function __construct(
        public string $status,
        public string $message,
        public ?string $redirect = null,
    ) {}

    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    /** @return array{status: string, message: string, redirect?: string} */
    public function toArray(): array
    {
        $arr = ['status' => $this->status, 'message' => $this->message];
        if ($this->redirect !== null) {
            $arr['redirect'] = $this->redirect;
        }

        return $arr;
    }
}
