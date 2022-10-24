<?php

namespace App\Validation;

use App\Models\Chirp;

class StoreChirpValidator extends Validator
{
    public function getErrors($input): array
    {
        $errors = [];

        if (isset($input['chirp_id']) && !Chirp::find($input['chirp_id'])) {
            $errors['chirp_id'] = "Can't reply to Chirp.";
        }

        if (empty($input['content'])) {
            $errors['content'] = 'A Chirp must not be empty.';
        } else if (strlen($input['content']) > 255) {
            $errors['content'] = 'A Chirp must be 255 characters or shorter.';
        }

        return $errors;
    }
}