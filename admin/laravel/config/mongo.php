<?php

return [

    'uri' => env('DB_MONGO_URI'),

    'database' => env('DB_MONGO_DATABASE', 'logs'),

    'collection' => env('MONGO_COLLECTION', 'laravel'),

];
