<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// The Laravel application lives beside public_html, outside the web root.
// If you rename the application folder, change it here too.
$appPath = __DIR__.'/../tricetech_app';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appPath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $appPath.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $appPath.'/bootstrap/app.php';

// This directory is the web root, not <application>/public. Laravel must know
// that so @vite, public_path() and the compiled asset manifest resolve here.
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
