<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Validation\StoreUserValidator;

class UserController
{
    public function create()
    {
        view('auth/register');
    }
    
    public function store()
    {
        $validated = (new StoreUserValidator($_POST))->validated();

        sign_in(User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]));

        redirect('/');
    }
}