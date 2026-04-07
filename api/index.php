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

// TEST DATABASE CONNECTION
try {
    $host = $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? '';
    $port = $_ENV['DB_PORT'] ?? $_SERVER['DB_PORT'] ?? '4000';
    $db   = $_ENV['DB_DATABASE'] ?? $_SERVER['DB_DATABASE'] ?? 'test';
    $user = $_ENV['DB_USERNAME'] ?? $_SERVER['DB_USERNAME'] ?? '';
    $pass = $_ENV['DB_PASSWORD'] ?? $_SERVER['DB_PASSWORD'] ?? '';
    $ssl  = __DIR__ . '/../cacert.pem';

    if ($host) {
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
        $options = [
            PDO::MYSQL_ATTR_SSL_CA => $ssl,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        $pdo = new PDO($dsn, $user, $pass, $options);
        echo "Database Connection: SUCCESS\n";
    }
} catch (\PDOException $e) {
    echo "Database Connection: FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
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
