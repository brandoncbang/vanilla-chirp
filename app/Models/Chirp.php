<?php

namespace App\Models;

class Chirp extends Model
{
    public static string $table_name = 'chirps';

    public int $id;
    public int $user_id;
    public ?int $chirp_id;
    public string $content;
    public string $created_at;
    public string $updated_at;

    public ?User $user = null;
    public ?Chirp $parent = null;
    public ?array $replies = null;

    public static function loadRelations(array $relations, array $chirps): array
    {
        if (in_array('user', $relations)) {
            $chirps = static::loadBelongsTo(
                User::class,
                'user_id',
                'user',
                $chirps,
            );
        }

        if (in_array('parent', $relations)) {
            $chirps = static::loadBelongsTo(
                Chirp::class,
                'chirp_id',
                'parent',
                $chirps,
                ['user'],
            );
        }

        if (in_array('replies', $relations)) {
            $chirps = static::loadHasMany(
                Chirp::class,
                'chirp_id',
                'replies',
                $chirps,
                ['replies', 'user'],
            );
        }

        return $chirps;
    }

    public static function paginate($where = 'where chirp_id is null', $order_by = null, $order = 'asc', $limit = 15, $with = []): array
    {
        return parent::paginate($where, $order_by, $order, $limit, $with);
    }

    public function getReplyCount()
    {
        return count($this->replies ?? []);
    }

    public function getDeletePath(): string
    {
        return $this->getPath() . '/delete';
    }

    public function getPath(): string
    {
        return "/chirps/{$this->id}";
    }

    public function getCreatedAt(): \DateTime
    {
        return new \DateTime($this->created_at);
    }
}