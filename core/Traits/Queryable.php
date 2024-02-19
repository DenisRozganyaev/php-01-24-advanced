<?php

namespace Core\Traits;

use mysql_xdevapi\Expression;
use PDO;
use function Core\db;

trait Queryable
{
    static public string|null $tableName = null;
    static protected string $query = '';
    protected array $commands = [];


    static public function select(array $columns = ['*']): static
    {
        static::resetQuery();
        static::$query = "SELECT " . implode(', ', $columns) . " FROM " . static::$tableName . " ";

        $obj = new static;
        $obj->commands[] = 'select';

        return $obj;
    }

    static public function all(): array
    {
        return static::select()->get();
    }

    static public function find(int $id): static|false
    {
        $query = db()->prepare("SELECT * FROM " . static::$tableName . " WHERE id = :id");
        $query->bindParam('id', $id);
        $query->execute();

        return $query->fetchObject(static::class);
    }

    static public function findBy(string $column, mixed $value): static|false
    {
        $query = db()->prepare("SELECT * FROM " . static::$tableName . " WHERE $column = :$column");
        $query->bindParam($column, $value);
        $query->execute();

        return $query->fetchObject(static::class);
    }

    static public function create(array $fields): null|static
    {
        $params = static::prepareQueryParams($fields);
        $query = db()->prepare("INSERT INTO " . static::$tableName . " ($params[keys]) VALUES ($params[placeholders])");

        if (!$query->execute($fields)) {
            return null;
        }

        return static::find(db()->lastInsertId());
    }

    static protected function prepareQueryParams(array $fields): array
    {
        $keys = array_keys($fields);
        $placeholder = preg_filter('/^/', ':', $keys);

        return [
            'keys' => implode(', ', $keys),
            'placeholders' => implode(', ', $placeholder)
        ];
    }

    static protected function resetQuery(): void
    {
        static::$query = '';
    }

    public function get(): array
    {
        return db()->query(static::$query)->fetchAll(PDO::FETCH_CLASS, static::class);
    }
}
