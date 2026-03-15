<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultCheckControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_result_check_form_renders_without_params(): void
    {
        $response = $this->get(route('result.check'));

        $response->assertStatus(200);
        $response->assertViewIs('result.check-result');
        $response->assertSee('Check Result', false);
        $response->assertSee('Student ID', false);
    }

    public function test_result_check_with_invalid_reg_number_redirects_with_error(): void
    {
        $response = $this->get(route('result.check', [
            'term' => 'First Term',
            'session' => '2024',
            'class' => 'JSS 1',
            'reg_number' => 'NONEXISTENT',
        ]));

        $response->assertRedirect(route('result.check'));
        $response->assertSessionHas('error', 'A student with this ID Number does not exist.');
    }

    public function test_result_check_with_missing_params_shows_form(): void
    {
        $response = $this->get(route('result.check', [
            'term' => 'First Term',
            'session' => '2024',
            'class' => 'JSS 1',
            // reg_number missing
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('result.check-result');
    }
}
