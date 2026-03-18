<?php

/*
 * Pest configuration. Feature tests use Tests\TestCase which uses RefreshDatabase where needed.
 */

uses(Illuminate\Foundation\Testing\RefreshDatabase::class)->in('Feature');
