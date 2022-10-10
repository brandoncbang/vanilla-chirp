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

        // TODO: Override method with one that stores a hash of the password.
        sign_in(User::create([
            $validated['name'], $validated['email'], $validated['password'],
        ]));

        redirect('/');
    }
}