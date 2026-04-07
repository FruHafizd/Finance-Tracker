<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Force debug mode for investigation
$_ENV['APP_DEBUG'] = 'true';
$_ENV['APP_ENV'] = 'production';

// Check for vendor folder
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "Fatal Error: vendor/autoload.php not found. Did composer install run?";
    exit;
}

// Check for APP_KEY
if (empty(env('APP_KEY')) && empty($_ENV['APP_KEY'])) {
    echo "Fatal Error: APP_KEY is not set in Environment Variables.";
    exit;
}

// Ensure /tmp directories exist for Laravel's read-only file system on Vercel
$directories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        if (!mkdir($directory, 0755, true)) {
             echo "Fatal Error: Failed to create directory: $directory";
             exit;
        }
    }
}

// Override storage path using $_ENV
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

// Forward Vercel requests to normal index.php
require __DIR__ . '/../public/index.php';
