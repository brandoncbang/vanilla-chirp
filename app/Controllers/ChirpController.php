<?php

namespace App\Controllers;

use App\Models\Chirp;
use App\Support\HttpException;
use App\Validation\StoreChirpValidator;

class ChirpController
{
    public function index()
    {
        view('chirp/index', [
            'chirps' => Chirp::paginate(order_by: 'id', order: 'desc', with: [
                'user', 'parent', 'replies'
            ]),
        ]);
    }

    public function store()
    {
        $validated = (new StoreChirpValidator($_POST))->validated();

        Chirp::create([
            'user_id' => current_user()->id,
            'chirp_id' => $validated['chirp_id'] ?? null,
            'content' => $validated['content'],
        ]);

        redirect('/chirps');
    }

    public function show()
    {
        [$id] = path_params('/chirps/{id}');

        view('chirp/show', [
            'chirp' => Chirp::findOrFail($id, with: ['user', 'parent', 'replies']),
        ]);
    }

    public function destroy()
    {
        [$id] = path_params('/chirps/{id}/delete');

        $chirp = Chirp::findOrFail($id);

        if ($chirp->user_id !== current_user()->id) {
            throw new HttpException('Unauthorized', 401);
        }

        $chirp->delete();

        redirect('/chirps');
    }

    public function __construct()
    {
        authenticate();
    }
}