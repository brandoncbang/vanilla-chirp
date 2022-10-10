<?php

namespace App\Controllers;

class ChirpController
{
    public function index()
    {
        return view('chirp/index');
    }

    public function store()
    {
        
    }

    public function show()
    {
        
    }

    public function destroy()
    {
        
    }

    public function __construct()
    {
        authenticate();
    }
}