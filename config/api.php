<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình chung cho tất cả các API endpoints
    |
    */

    'pagination' => [
        'default_per_page' => env('API_DEFAULT_PER_PAGE', 10),
        'max_per_page' => env('API_MAX_PER_PAGE', 100),
        'min_per_page' => env('API_MIN_PER_PAGE', 1),
    ],

    'sorting' => [
        'default_sort_by' => env('API_DEFAULT_SORT_BY', 'id'),
        'default_sort_order' => env('API_DEFAULT_SORT_ORDER', 'asc'),
        'allowed_sort_orders' => ['asc', 'desc'],
    ],

    'response' => [
        'include_meta' => env('API_INCLUDE_META', true),
        'include_links' => env('API_INCLUDE_LINKS', true),
        'default_timezone' => env('API_DEFAULT_TIMEZONE', 'UTC'),
    ],

    'rate_limiting' => [
        'enabled' => env('API_RATE_LIMITING_ENABLED', true),
        'max_attempts' => env('API_RATE_LIMIT_MAX_ATTEMPTS', 60),
        'decay_minutes' => env('API_RATE_LIMIT_DECAY_MINUTES', 1),
    ],

    'caching' => [
        'enabled' => env('API_CACHING_ENABLED', false),
        'ttl' => env('API_CACHE_TTL', 300), // 5 minutes
    ],

];