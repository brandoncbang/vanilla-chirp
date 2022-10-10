<?php

namespace App\Validation;

use App\Models\User;

class StoreSessionValidator extends Validator
{
    protected function getErrors($input): array
    {
        $errors = [];

        if (empty($input['email'])) {
            $errors['email'] = 'An email address is required.';
        } else if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if (empty($input['password'])) {
            $errors['password'] = 'A password is required.';
        }

        if (!empty($errors)) {
            return $errors;
        }

        if (!User::findByEmailAndPassword($input['email'], $input['password'])) {
            $errors['user'] = 'Incorrect email or password.';
        }

        return $errors;
    }
}