<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\ResultPublishService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultPublishServiceTest extends TestCase
{
    use RefreshDatabase;

    private ResultPublishService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ResultPublishService;
    }

    public function test_publish_returns_error_when_no_students_in_class(): void
    {
        $result = $this->service->publish('SS1', '1', '2024/2025', 'Admin');

        $this->assertSame('error', $result['status']);
        $this->assertStringContainsString('haven\'t been uploaded', $result['message']);
    }
}
