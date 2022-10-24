<?php

namespace App\Models;

use App\Models\Traits\LoadsRelations;
use App\Support\HttpException;

class Model
{
    use LoadsRelations;

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

    public static function find(int|string $id, array $with = []): ?static
    {
        $table_name = static::$table_name;
        $primary_key = static::$primary_key;

        $row = static::select("select * from {$table_name} where {$primary_key} = ?", [$id])[0] ?? null;

        if ($row) {
            $row = static::loadRelations($with, [$row])[0];
        }

        return $row;
    }

    /**
     * Get the model by id if it exists, generate a 404 error otherwise.
     *
     * @throws HttpException
     */
    public static function findOrFail(int|string $id, array $with = []): static
    {
        $row = static::find($id, $with);

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
        return db()->select($query, $parameters, static::class) ?? [];
    }

    public static function paginate(string|null $where = null, string|null $order_by = null, string $order = 'asc', int $limit = 15, array $with = []): array
    {
        if (!$order_by) {
            $order_by = static::$primary_key;
        }

        if ($where) {
            $where = " {$where}";
        }

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

        $count = db()->scalar("select count(*) from {$table_name}{$where}");
        $start = ($page - 1) * $limit;

        if ($start > $count) {
            $start = floor($count / $start);
        }

        $end = $start + $limit;

        return static::loadRelations($with, static::select("select * from {$table_name}{$where} order by {$order_by} {$order} limit {$start}, {$end}"));
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

    public function delete(): int
    {
        return static::destroy($this->{static::$primary_key});
    }
}