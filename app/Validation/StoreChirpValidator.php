<?php

namespace App\Validation;

class StoreChirpValidator extends Validator
{
    public function getErrors($input): array
    {
        $errors = [];

        if (empty($input['content'])) {
            $errors['content'] = 'A Chirp must not be empty.';
        } else if (strlen($input['content']) > 255) {
            $errors['content'] = 'A Chirp must be 255 characters or shorter.';
        }

        return $errors;
    }
}