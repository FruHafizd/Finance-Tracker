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
if (empty($_ENV['APP_KEY']) && empty($_SERVER['APP_KEY'])) {
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
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/storage/bootstrap/cache/packages.php';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/storage/bootstrap/cache/services.php';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/storage/bootstrap/cache/config.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/storage/bootstrap/cache/routes.php';

// Ensure cache directory exists in /tmp
if (!is_dir('/tmp/storage/bootstrap/cache')) {
    mkdir('/tmp/storage/bootstrap/cache', 0755, true);
}

// Forward Vercel requests to normal index.php
try {
    require __DIR__ . '/../public/index.php';
} catch (\Exception $e) {
    echo "Caught Exception: " . $e->getMessage() . "\n";
    if ($e->getPrevious()) {
        echo "Previous Exception: " . $e->getPrevious()->getMessage() . "\n";
    }
    echo "Trace: " . $e->getTraceAsString();
} catch (\Error $e) {
    echo "Caught Error: " . $e->getMessage() . "\n";
    if ($e->getPrevious()) {
        echo "Previous Error: " . $e->getPrevious()->getMessage() . "\n";
    }
    echo "Trace: " . $e->getTraceAsString();
}
