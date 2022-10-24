<?php

use App\Support\DB;

function dd(mixed $var) {
    var_dump($var);
    die();
}

function redirect(string $to)
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

function param_path(string $signature): string|false
{
    $request_path = request_path();
    $pattern = preg_replace('/{(.+?)}/', '(?<$1>[^/]+?)', $signature);
    $regex = "@^{$pattern}$@";

    if (preg_match($regex, $request_path)) {
        return $request_path;
    }

    return false;
}

function path_params(string $signature): array
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

function flash(string $key, mixed $data)
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

function view(string $path, array $data = [])
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

function button_to(string $action = '', string $text = 'Label', array $data = [], string $method = 'POST', bool $confirm = false)
{
    $data_fields = '';

    foreach ($data as $name => $value) {
        $data_fields .= <<<HTML
        <input type="hidden" name="{$name}" value="{$value}">
        HTML;
    }

    $on_click = '';

    if ($confirm) {
        $on_click = " onclick=\"return confirm('Are you sure?')\"";
    }

    return <<<HTML
    <form action="{$action}" method="{$method}" style="display: inline-block"{$on_click}>
        <!-- TODO: Add CSRF token! -->
        {$data_fields}

        <button type="submit">{$text}</button>
    </form>
    HTML;
}

function delete_button(string $action = '', string $method = 'POST'): string
{
    return button_to($action, 'Delete', method: $method);
}

function config(): array
{
    return require __DIR__ . '/../../config.php';
}

function db(): DB
{
    return DB::getInstance();
}