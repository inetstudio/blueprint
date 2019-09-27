<?php

return array(
    'dsn' => env('SENTRY_LARAVEL_DSN'),

    'release' => file_exists(base_path('public/release')) ? trim(str_replace(['\'', "\n"], ['', ''], file_get_contents(base_path('public/release')))).'-MAKEUP' : '',

    // Capture bindings on SQL queries
    'breadcrumbs.sql_bindings' => true,

    'send_default_pii' => true,
);
