<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Values
    |--------------------------------------------------------------------------
    |
    */
    'default' => [

        // paginate default count per page
        'paginate' => env('REPOSITORY_DEFAULT_PAGINATE', 25),

        // get results with trashed
        'with_trashed' => env('REPOSITORY_WITH_TRASHED', false),
        
    ],

];
