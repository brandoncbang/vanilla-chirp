<?php

namespace App\Support;

class DB extends \PDO
{
    protected static DB $instance;

    public static function getInstance(): DB
    {
        if (!isset(static::$instance)) {
            $config = config()['db'];
            $connection = $config['connection'];
            $host = $config['host'];
            $port = $config['port'];
            $database = $config['database'];

            $dsn = "{$connection}:host={$host};port={$port};dbname={$database};charset=utf8";

            static::$instance = new DB($dsn, $config['username'], $config['password']);
        }

        return static::$instance;
    }

    public function select($query, $parameters = [], $model = null): bool|null|array
    {
        if (empty($parameters)) {
            $statement = $this->query($query);
        } else {
            $statement = $this->prepare($query);
            $statement->execute($parameters);
        }

        if (!is_null($model)) {
            $statement->setFetchMode(static::FETCH_CLASS, $model);
        }

        return $statement->fetchAll();
    }

    public function scalar($query, $parameters = [], $model = null): mixed
    {
        return array_values($this->select($query, $parameters, $model)[0])[0];
    }

    public function insert($query, $parameters = [])
    {
        $this->statement($query, $parameters);
    }

    public function update($query, $parameters = []): int
    {
        if (empty($parameters)) {
            $statement = $this->query($query);
        } else {
            $statement = $this->prepare($query);
            $statement->execute($parameters);
        }

        return $statement->rowCount();
    }

    public function delete($query, $parameters = []): int
    {
        if (empty($parameters)) {
            $statement = $this->query($query);
        } else {
            $statement = $this->prepare($query);
            $statement->execute($parameters);
        }

        return $statement->rowCount();
    }

    public function statement($query, $parameters = [])
    {
        if (empty($parameters)) {
            $this->query($query);
        } else {
            $statement = $this->prepare($query);
            $statement->execute($parameters);
        }
    }
}