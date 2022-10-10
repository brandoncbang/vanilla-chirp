<?php

namespace App\Models;

use App\Support\HttpException;

class Model
{
    public static string $table_name;
    public static string $primary_key = 'id';

    public static function create(array $attributes): static
    {
        $table_name = static::$table_name;
        $primary_key = static::$primary_key;
        $columns = implode(', ', array_keys($attributes));
        $values = array_values($attributes);

        $placeholders = implode(', ', array_fill(0, 3, '?'));

        db()->insert("insert into {$table_name} ({$columns}) values ({$placeholders})", [
            ...$values,
        ]);

        return static::select("select * from {$table_name} where {$primary_key} = last_insert_id()")[0];
    }

    public static function find(int $id): ?static
    {
        $table_name = static::$table_name;
        $primary_key = static::$primary_key;

        return static::select("select * from {$table_name} where {$primary_key} = ?", [$id])[0] ?? null;
    }

    /**
     * Get the model by id if it exists, generate a 404 error otherwise.
     *
     * @throws HttpException
     */
    public static function findOrFail(int $id): static
    {
        $row = static::find($id);

        if (is_null($row)) {
            throw new HttpException(404, 'Not found.');
        }

        return $row;
    }

    /**
     * Make a SELECT statement and get the results as an array of the model class.
     *
     * @return static[]
     */
    public static function select($query, $parameters = []): array
    {
        return db()->select($query, $parameters, static::class) ?? [];
    }
}