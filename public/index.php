<?php

use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;

define('LARAVEL_START', microtime(true));

$allowedOrigins = array(
    '*',
);

if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] != '') {
    $origin = $_SERVER['HTTP_ORIGIN'];
    if (in_array($origin, $allowedOrigins)) {
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1728000');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, X-Requested-With, Content-Range, Content-Disposition, Content-Description, x-xsrf-token, ip');
    }
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__ . '/../bootstrap/app.php')
    ->handleRequest(Request::capture());
