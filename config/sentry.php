<?php

return array(
    'dsn' => env('SENTRY_DSN', 'https://public:secret@sentry.example.com/1'),

    // TODO @appkr Duplicate. Move APP_VERSION to .env and Write it dynamically
    // capture release as git sha
    'release' => trim(exec('git log --pretty="%h" -n1 HEAD')) ?: null,

    // Capture bindings on SQL queries
    'breadcrumbs.sql_bindings' => true,

    // Capture default user context
    'user_context' => true,
);
