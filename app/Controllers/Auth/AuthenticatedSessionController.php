<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Validation\StoreSessionValidator;

class AuthenticatedSessionController
{
    public function create()
    {
        view('auth/login');
    }

    public function store()
    {
        $validated = (new StoreSessionValidator($_POST))->validated();

        sign_in(User::findByEmail($validated['email']));

        redirect('/');
    }

    public function destroy()
    {
        sign_out();

        redirect('/');
    }
}