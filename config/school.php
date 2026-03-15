<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Segment Weights (Result Calculation)
    |--------------------------------------------------------------------------
    */
    /* Single term upload uses Term = full score (CA+Assign+Exam). Legacy rows may still use First/Second/Third. */
    'segment_weights' => [
        'Term' => 1.00,
        'First' => 0.20,
        'Second' => 0.20,
        'Third' => 0.60,
    ],
    'result_segment_term' => 'Term',

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

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'staff' => 15,
        'teachers' => 15,
        'students' => 25,
        'news' => 6,
        'notifications' => 20,
    ],

    'school_email' => 'support@pjp.school',
];
