<?php

namespace App\Validation;

class StoreUserValidator extends Validator
{
    protected function getErrors($input): array
    {
        $errors = [];

        if (empty($input['name'])) {
            $errors[] = 'A name is required.';
        }

        if (empty($input['email'])) {
            $errors[] = 'An email address is required.';
        }

        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (empty($input['password'])) {
            $errors[] = 'A password is required.';
        }

        return $errors;
    }
}