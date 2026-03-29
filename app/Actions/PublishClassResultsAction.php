<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTO\PublishResultResponseDTO;
use App\Services\ResultPublishService;
use Throwable;

final readonly class PublishClassResultsAction
{
    public function __construct(
        private ResultPublishService $resultPublishService
    ) {}

    /**
     * @throws Throwable
     */
    public function execute(string $class, string $term, string $session, string $adminName): PublishResultResponseDTO
    {
        $response = $this->resultPublishService->publish($class, $term, $session, $adminName);

        return new PublishResultResponseDTO(
            status: $response['status'],
            message: $response['message'],
            redirect: null,
        );
    }
}
