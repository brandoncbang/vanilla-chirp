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

function request_method(): string
{
    return $_SERVER['REQUEST_METHOD'];
}

function request_path(): string
{
    return rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';
}

function param_path($signature): string|false
{
    $request_path = request_path();
    $pattern = preg_replace('/{(.+?)}/', '(?<$1>[^/]+?)', $signature);
    $regex = "@^{$pattern}$@";

    if (preg_match($regex, $request_path)) {
        return $request_path;
    }

    return false;
}

function path_params($signature): array
{
    $request_path = request_path();
    $pattern = preg_replace('/{(.+?)}/', '(?<$1>[^/]+?)', $signature);
    $regex = "@^{$pattern}$@";
    $params = [];

    preg_match_all($regex, $request_path, $matches);

    foreach ($matches as $key => $value) {
        if (is_string($key)) {
            $params[] = $value[0];
        }
    }

    return $params;
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

function e(string|null $string): string
{
    return htmlspecialchars($string ?? '');
}

function view($path, $data = [])
{
    extract($data);
    require __DIR__ . "/../../views/{$path}.php";
    exit;
}

function link_to(string|object $link, string $text)
{
    if (method_exists($link, 'getPath')) {
        $link = $link->getPath();
    }

    return <<<HTML
        <a href="{$link}">{$text}</a>
        HTML;
}

function delete_button($action = '', $method = 'POST'): string
{
    return <<<HTML
        <form action="{$action}" method="{$method}" onclick="return confirm('Are you sure?')"
              style="display: inline-block">
            <!-- TODO: Add CSRF token! -->

            <button type="submit">Delete</button>
        </form>
        HTML;
}

function config(): array
{
    return require __DIR__ . '/../../config.php';
}

function db(): DB
{
    return DB::getInstance();
}