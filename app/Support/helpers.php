<?php

use App\Support\DB;

function dd($var) {
    var_dump($var);
    die();
}

function redirect($to)
{
    header("Location: {$to}");
    exit;
}

function param_path($signature): string|false
{
//    rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';
    return false;
}

function flash($key, $data)
{
    $_SESSION[$key] = $data;

    if (!array_key_exists('_flashed_new', $_SESSION)) {
        $_SESSION['_flashed_new'] = [];
    }

    $_SESSION['_flashed_new'][] = $key;
}

function errors(string $key = null): string|array
{
    $errors = $_SESSION['_errors'] ?? [];

    if ($key) {
        return (string) ($errors[$key] ?? null);
    }

    return $errors;
}

function has_errors(): bool
{
    return !empty(errors());
}

function old(string $name = null): string|array
{
    $old = $_SESSION['_old'] ?? [];

    if ($name) {
        return (string) ($old[$name] ?? null);
    }

    return $old;
}

function e($string): string
{
    return htmlspecialchars($string);
}

function view($path, $data = [])
{
    extract($data);
    require __DIR__ . "/../../views/{$path}.php";
    exit;
}

function config(): array
{
    return require __DIR__ . '/../../config.php';
}

function db(): DB
{
    return DB::getInstance();
}