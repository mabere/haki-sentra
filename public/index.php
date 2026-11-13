<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// In shared hosting, Laravel source is outside public_html. Locally, the app
// root is the parent of public/.
$appBasePath = realpath(__DIR__.'/../../sentrahaki');

if (!$appBasePath || !is_file($appBasePath.'/vendor/autoload.php')) {
    $appBasePath = realpath(__DIR__.'/..');
}

if (!$appBasePath || !is_file($appBasePath.'/vendor/autoload.php')) {
    http_response_code(500);
    exit('Laravel application path is not configured correctly.');
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appBasePath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $appBasePath . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $appBasePath . '/bootstrap/app.php';

if (method_exists($app, 'usePublicPath')) {
    $app->usePublicPath(__DIR__);
} else {
    $app->bind('path.public', fn () => __DIR__);
}

$app->handleRequest(Request::capture());
