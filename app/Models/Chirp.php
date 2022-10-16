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

    public static function loadRelations(array $chirps): array
    {
        $chirps = static::loadBelongsTo(
            User::class,
            'user_id',
            'user',
            $chirps,
        );

        $chirps = static::loadBelongsTo(
            Chirp::class,
            'chirp_id',
            'parent',
            $chirps,
        );

        return $chirps;
    }

    public function getPath(): string
    {
        return "/chirps/{$this->id}";
    }

    public function getDeletePath(): string
    {
        return $this->getPath() . '/delete';
    }

    public function getCreatedAt(): \DateTime
    {
        return new \DateTime($this->created_at);
    }
}