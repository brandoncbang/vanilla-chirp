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

        $placeholders = implode(', ', array_fill(0, count($values), '?'));

        db()->insert("insert into {$table_name} ({$columns}) values ({$placeholders})", [
            ...$values,
        ]);

        return static::select("select * from {$table_name} where {$primary_key} = last_insert_id()")[0];
    }

    public static function find(int|string $id): ?static
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
    public static function findOrFail(int|string $id): static
    {
        $row = static::find($id);

        if (is_null($row)) {
            throw new HttpException('Not found', 404);
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
        return static::loadRelations(db()->select($query, $parameters, static::class) ?? []);
    }

    public static function paginate($order_by, $order = 'asc', $limit = 15): array
    {
        $table_name = static::$table_name;

        $valid_orders = [
            'desc',
            'asc',
        ];

        if (!in_array($order, $valid_orders)) {
            throw new \Error('Argument `$order` must be one of: "desc", "asc".');
        }

        $page = (int) ($_GET['page'] ?? 1);

        if ($page < 1) {
            $page = 1;
        }

        $count = db()->scalar("select count(*) from {$table_name}");
        $start = ($page - 1) * $limit;

        if ($start > $count) {
            $start = floor($count / $start);
        }

        $end = $start + $limit;

        return static::select("select * from {$table_name} order by {$order_by} {$order} limit {$start}, {$end}");
    }

    public static function destroy(int|string $id): int
    {
        $table_name = static::$table_name;
        $primary_key = static::$primary_key;

        return db()->delete("delete from {$table_name} where {$primary_key} = ?", [$id]);
    }

    public static function destroyOrFail(int $id)
    {
        if (static::destroy($id) === 0) {
            throw new HttpException(404, 'Not found.');
        }
    }

    public static function loadRelations(array $models): array
    {
        return $models;
    }

    public static function loadBelongsTo($belongs_to, string $foreign_key, string $property, array $models): array
    {
        $result = $models;

        $belongs_to_ids = array_unique(array_map(fn ($model) => $model->{$foreign_key}, $models));
        $belongs_to_ids_str = implode(', ', $belongs_to_ids);
        $belongs_to_table = $belongs_to::$table_name;
        $belongs_to_pk = $belongs_to::$primary_key;

        if (empty($models) || empty($belongs_to_ids) || in_array(null, $belongs_to_ids)) {
            return $result;
        }

        $belongs_to_rows = $belongs_to::select(
            "select * from {$belongs_to_table} where {$belongs_to_pk} in ({$belongs_to_ids_str})"
        );

        foreach ($result as $model) {
            foreach ($belongs_to_rows as $belongs_to_row) {
                if ($model->{$foreign_key} === $belongs_to_row->{$belongs_to_pk}) {
                    $model->{$property} = $belongs_to_row;
                }
            }
        }

        return $result;
    }

    public function delete()
    {
        return static::destroy($this->{static::$primary_key});
    }
}