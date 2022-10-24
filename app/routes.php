<?php

use App\Controllers\Auth\AuthenticatedSessionController;
use App\Controllers\Auth\UserController;
use App\Controllers\ChirpController;
use App\Controllers\HomeController;

$method = $_SERVER['REQUEST_METHOD'];
$path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';

return match ([$method, $path]) {
    ['GET', '/'] => [HomeController::class, 'show'],

    ['GET', '/login'] => [AuthenticatedSessionController::class, 'create'],
    ['POST', '/login'] => [AuthenticatedSessionController::class, 'store'],
    ['POST', '/logout'] => [AuthenticatedSessionController::class, 'destroy'],

    ['GET', '/register'] => [UserController::class, 'create'],
    ['POST', '/register'] => [UserController::class, 'store'],

    ['GET', '/chirps'] => [ChirpController::class, 'index'],
    ['POST', '/chirps/create'] => [ChirpController::class, 'store'],
    ['GET', param_path('/chirps/{id}')] => [ChirpController::class, 'show'],
    ['POST', param_path('/chirps/{id}/delete')] => [ChirpController::class, 'destroy'],

    default => throw new \App\Support\HttpException('Not found', 404),
};