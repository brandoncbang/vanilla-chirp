<?php

namespace App\Controllers;

class HomeController
{
    public function show()
    {
        redirect('/chirps');
    }

    public function __construct()
    {
        authenticate();
    }
}