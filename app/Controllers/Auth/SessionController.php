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
        $validated = (new StoreSessionValidator($_POST))->validated();

        // TODO: Get hash of password to compare with stored.
        sign_in(User::findByEmailAndPassword($validated['email'], $validated['password']));

        redirect('/');
    }

    public function destroy()
    {
        sign_out();

        redirect('/');
    }
}