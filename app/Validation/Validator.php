<?php

namespace App\Validation;

class Validator
{
    public function __construct(protected $input) {}

    protected function getErrors($input): array
    {
        return [];
    }

    public function validated(): array
    {
        $errors = $this->getErrors($this->input);

        if (empty($errors)) {
            return $this->input;
        }

        flash('errors', $errors);
        flash('old', $this->input);

        redirect($_SERVER['HTTP_REFERER']);

        return [];
    }
}