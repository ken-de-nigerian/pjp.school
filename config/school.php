<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Branding (logo asset is storage/app/public/{logo_file})
    |--------------------------------------------------------------------------
    */
    'logo_file' => 'logo/logo.jpg',

    /** Primary wordmark line (shown uppercase in UI). */
    'brand_line1' => 'Pope John Paul II',

    /** Secondary line under the primary (shown uppercase, smaller). */
    'brand_line2' => 'Model Sec Sch',
    /*
    |--------------------------------------------------------------------------
    | Post-migration: segment column value when segment logic is not used
    |--------------------------------------------------------------------------
    */
    'no_segment' => 'No Segment',

    /*
    |--------------------------------------------------------------------------
    | House List (for student grouping)
    |--------------------------------------------------------------------------
    */
    'houses' => [
        'St. Sylvester',
        'St. Cecilia',
        'St. John Bosco',
        'St. Philomena',
        'Bishop Chikwe',
        'St. Maria Goretti',
    ],

    'school_email' => 'support@pjp.school',
    'school_phone' => '+234 (806) 983-0352',

    /*
    |--------------------------------------------------------------------------
    | AJAX success: delay before full-page reload or redirect (ms)
    |--------------------------------------------------------------------------
    | Lets iziToast stay visible after fetch()/XHR success. Env: SCHOOL_AJAX_RELOAD_DELAY_MS
    */
    'ajax_reload_delay_ms' => max(0, (int) env('SCHOOL_AJAX_RELOAD_DELAY_MS', 2800)),
];
