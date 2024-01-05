<?php

namespace App\Models;

use App\Database\Connection;
use JsonSerializable;
use PDO;
use ReturnTypeWillChange;

abstract class BaseModel implements JsonSerializable {
    /**
     * @var string
     */
    protected static string $table;

    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * @var array
     */
    protected array $fields = [];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = []) {
        foreach ($attributes as $attribute => $value) {
            if (in_array($attribute, $this->fields)) {
                $this->attributes[$attribute] = $value;
            }
        }
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name) {
        return $this->attributes[$name] ?? null;
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value) {
        if (in_array($name, $this->fields)) {
            $this->attributes[$name] = $value;
        }
    }

    /**
     * @param array $attributes
     * @return static
     */
    public static function create(array $attributes): static
    {
        $model = new static($attributes);
        $model->insert();
        return $model;
    }

    /**
     * @return void
     */
    protected function insert(): void
    {
        $pdo = Connection::connect();
        $keys = array_keys($this->attributes);
        $placeholders = ':' . implode(', :', $keys);
        $sql = "INSERT INTO " . static::$table . " (" . implode(', ', $keys) . ") VALUES (" . $placeholders . ")";
        $stmt = $pdo->prepare($sql);
        foreach ($this->attributes as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        $this->attributes['id'] = $pdo->lastInsertId();
    }

    /**
     * @return void
     */
    public function save(): void
    {
        if (isset($this->attributes['id'])) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    /**
     * @return void
     */
    protected function update(): void
    {
        $pdo = Connection::connect();
        $fieldsToUpdate = array_filter(array_keys($this->attributes), function ($key) {
            return $key !== 'id';
        });
        $setString = implode(', ', array_map(fn($field) => "$field = :$field", $fieldsToUpdate));
        $sql = "UPDATE " . static::$table . " SET $setString WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        foreach ($this->attributes as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $pdo = Connection::connect();
        $stmt = $pdo->prepare("DELETE FROM " . static::$table . " WHERE id = :id");
        $stmt->bindValue(':id', $this->attributes['id']);
        $stmt->execute();
    }

    /**
     * @return array|false
     */
    public static function all(): false|array
    {
        $pdo = Connection::connect();
        $stmt = $pdo->query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll(PDO::FETCH_CLASS, get_called_class());
    }
    /**
     * @param int $id
     * @return mixed
     */
    public static function find(int $id): mixed
    {
        $pdo = Connection::connect();
        $stmt = $pdo->prepare("SELECT * FROM " . static::$table . " WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetch();
    }

    /**
     * @return array
     */
    #[ReturnTypeWillChange] public function jsonSerialize(): array
    {
        return array_filter($this->attributes, function ($value) {
            return $value !== null;
        });
    }
}
