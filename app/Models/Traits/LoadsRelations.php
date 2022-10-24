<?php

namespace App\Models\Traits;

use App\Models\Model;

trait LoadsRelations
{
    public static function loadRelations(array $relations, array $models): array
    {
        return $models;
    }

    /**
     * @param class-string<Model> $related The qualified class name of the model that this model belongs to.
     * @param string $foreign_key The foreign key that this model uses to identify the foreign row it belongs to.
     * @param string $property The property to set with the related model once loaded.
     * @param array $rows An array of this model to load the relationship into.
     * @param array $with Relations to load on the model that this model belongs to.
     * @return array
     */
    public static function loadBelongsTo(mixed $related, string $foreign_key, string $property, array $rows, array $with = []): array
    {
        $results = $rows;

        $related_ids = array_unique(array_map(fn ($row) => $row->{$foreign_key}, $rows));
        $related_ids_sql = implode(', ', $related_ids);
        $related_table = $related::$table_name;
        $related_primary_key = $related::$primary_key;

        if (empty($rows) || empty($related_ids) || in_array(null, $related_ids)) {
            return $results;
        }

        $related_rows = $related::select(
            "select * from {$related_table} where {$related_primary_key} in ({$related_ids_sql})",
        );

        if (!empty($with)) {
            $related_rows = $related::loadRelations($with, $related_rows);
        }

        foreach ($results as $row) {
            foreach ($related_rows as $related_row) {
                if ($row->{$foreign_key} === $related_row->{$related_primary_key}) {
                    $row->{$property} = $related_row;
                }
            }
        }

        return $results;
    }

    public static function loadHasMany(mixed $related, string $foreign_key, string $property, array $rows, $with = []): array
    {
        $results = $rows;

        $row_ids = array_unique(array_map(fn ($row) => $row->{static::$primary_key}, $rows));
        $row_ids_sql = implode(', ', $row_ids);

        $related_table = $related::$table_name;

        if (empty($rows)) {
            return $results;
        }

        $related_rows = $related::select("select * from {$related_table} where {$foreign_key} in ({$row_ids_sql})");

        if (!empty($with)) {
            $related_rows = $related::loadRelations($with, $related_rows);
        }

        foreach ($results as $row) {
            foreach ($related_rows as $related_row) {
                if ($row->{static::$primary_key} === $related_row->{$foreign_key}) {
                    $row->{$property}[] = $related_row;
                }
            }
        }

        return $results;
    }
}