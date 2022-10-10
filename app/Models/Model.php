<?php

namespace App\Models;

use App\Support\DB;

class Model
{
    public static string $table_name;
    public static string $primary_key = 'id';

    public static function create(array $attributes): static
    {
        $db = DB::getInstance();

        $table_name = static::$table_name;
        $primary_key = static::$primary_key;
        $columns = implode(', ', array_keys($attributes));
        $values = array_values($attributes);

        $placeholders = implode(', ', array_fill(0, 3, '?'));

        $db->insert("insert into {$table_name} ({$columns}) values ({$placeholders})", [
            ...$values,
        ]);

        return $db->select("select * from {$table_name} where {$primary_key} = last_insert_id()", model: static::class)[0];
    }

    public static function find(int $id): static
    {
        $db = DB::getInstance();

        $table_name = static::$table_name;
        $primary_key = static::$primary_key;

        return $db->select("select * from {$table_name} where {$primary_key} = ?", [$id], static::class)[0];
    }

    public static function select($query, $parameters): ?array
    {
        $db = DB::getInstance();

        return $db->select($query, $parameters, static::class);
    }
}