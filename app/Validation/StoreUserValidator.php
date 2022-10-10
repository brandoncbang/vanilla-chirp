<?php

namespace App\Validation;

use App\Models\User;

class StoreUserValidator extends Validator
{
    protected function getErrors($input): array
    {
        $errors = [];

        if (empty($input['name'])) {
            $errors['name'] = 'A name is required.';
        }

        if (empty($input['email'])) {
            $errors['email'] = 'An email address is required.';
        } else if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } else if (User::findByEmail($input['email'])) {
            $errors['email'] = 'A user with that email address already exists.';
        }

        if (empty($input['password'])) {
            $errors['password'] = 'A password is required.';
        }

        return $errors;
    }
}