<?php

namespace App\Validation;

use App\Models\User;

class StoreSessionValidator extends Validator
{
    protected function getErrors($input): array
    {
        $errors = [];

        if (empty($input['email'])) {
            $errors[] = 'An email address is required.';
        }

        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (empty($input['password'])) {
            $errors[] = 'A password is required.';
        }

        if (!empty($errors)) {
            return $errors;
        }

        if (!User::findByEmailAndPassword($input['email'], $input['password'])) {
            $errors[] = 'Incorrect email or password.';
        }

        return $errors;
    }
}