<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Validation\StoreUserValidator;

class UserController
{
    public function create()
    {
        return view('auth/register');
    }
    
    public function store()
    {
        $validated = (new StoreUserValidator($_POST))->validated();

        // TODO: hash password
        $user = User::create($validated);

        sign_in($user);

        redirect('/');
    }
}