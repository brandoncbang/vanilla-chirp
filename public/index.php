<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Remove old flash data:
foreach($_SESSION['_flashed_old'] ?? [] as $key) {
    unset($_SESSION[$key]);
}
$_SESSION['_flashed_old'] = [];

// Age new flash data:
if (array_key_exists('_flashed_new', $_SESSION)) {
    $_SESSION['_flashed_old'] = $_SESSION['_flashed_new'];
    $_SESSION['_flashed_new'] = [];
}

try {
    $handler = require_once __DIR__ . '/../app/routes.php';

    if (is_array($handler)) {
        [$controller, $action] = $handler;
        (new $controller)->$action();

        return;
    }

    if (is_callable($handler)) {
        $handler();

        return;
    }

    throw new Exception('Route has no proper handler.');
} catch (\App\Support\HttpException $e) {
    view('error', [
        'e' => $e,
    ]);
}