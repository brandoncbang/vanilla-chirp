<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Validation\StoreSessionValidator;

class SessionController
{
    public function create()
    {
        return view('auth/login');
    }

    public function store()
    {
        // TODO: Use password hash
        $validated = (new StoreSessionValidator($_POST))->validated();

        $user = User::findByEmailAndPassword($validated['email'], $validated['password']);

        sign_in($user);

        redirect('/');
    }

    public function destroy()
    {
        sign_out();

        redirect('/');
    }
}