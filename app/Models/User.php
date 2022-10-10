<?php

namespace App\Models;

class User extends Model
{
    public static string $table_name = 'users';

    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $created_at;
    public string $updated_at;

    public static function create(array $attributes): static
    {
        $modified = $attributes;

        if (isset($attributes['password'])) {
            $modified['password'] = password_hash($attributes['password'], PASSWORD_BCRYPT);
        }

        return parent::create($modified);
    }

    public static function findByEmail(string $email): ?static
    {
        return static::select('select * from users where email = ?', [
            $email,
        ])[0] ?? null;
    }
}