<?php

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

function e($string): string
{
    return htmlspecialchars($string);
}

function view($path, $data = [])
{
    extract($data);
    return require __DIR__ . "/../../views/{$path}.php";
}

function config(): array
{
    return require __DIR__ . '/../../config.php';
}