<?php

use App\Models\User;

function user_signed_in(): bool
{
    return array_key_exists('user_id', $_SESSION);
}

function authenticate()
{
    if (user_signed_in()) return;

    redirect('/login');
}

function current_user(): ?User
{
    if (!user_signed_in()) {
        return null;
    }

    return User::find($_SESSION['user_id']);
}

function sign_in(User $user)
{
    $_SESSION['user_id'] = $user->id;
}

function sign_out()
{
    unset($_SESSION['user_id']);
}