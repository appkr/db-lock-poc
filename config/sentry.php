<?php

return array(
    'dsn' => env('SENTRY_DSN', 'https://public:secret@sentry.example.com/1'),

    // capture release as git sha
    'release' => env('APP_VERSION', trim(exec('git log --pretty="%h" -n1 HEAD'))),

    // Capture bindings on SQL queries
    'breadcrumbs.sql_bindings' => true,

    // Capture default user context
    'user_context' => true,
);
