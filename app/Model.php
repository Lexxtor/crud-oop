<?php


abstract class Model
{
    public static $tableName;
    public $errors = [];

    /**
     * @var array значения атрибутов
     */
    protected $attributes = [];

    public function __construct($attributes = [])
    {
        $this->load($attributes);
    }

    public function load($attributes = [], $names = []): int
    {
        $filled = 0;
        foreach ($attributes as $name => $value) {
            if (!$names || in_array($name, $names)) {
                $this->attributes[$name] = $value;
                $filled++;
            }
        }
        return $filled;
    }

    /**
     * @return array ошибки валидации
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function save($validate = true)
    {
        if ($validate && $this->validate()) {
            return false;
        }

        if ($this->id) {
            return App::getInstance()->db->update(static::$tableName, $this->attributes, ['id' => $this->id]);
        } else {
            return App::getInstance()->db->insert(static::$tableName, $this->attributes);
        }
    }

    static public function findOne(int $id): ?Model
    {
        $data = App::getInstance()->db->query('SELECT * FROM `'.static::$tableName.'` WHERE id = :id', [$id]);

        if ($data) {
            return new static($data[0]);
        }

        return null;
    }

    static public function findAll(): array
    {
        return static::populate(
            App::getInstance()->db->query('SELECT * FROM `'.static::$tableName.'`')
        );
    }

    static public function deleteOne($id)
    {
        return App::getInstance()->db->query('DELETE FROM `'.static::$tableName.'` WHERE id = :id', [$id]);
    }

    static protected function populate(array $data): array
    {
        $models = [];
        foreach ($data as $values) {
            $models[] = new static($values);
        }
        return $models;
    }

    public function __get($attribute)
    {
        if (array_key_exists($attribute, $this->attributes)) {
            return $this->attributes[$attribute];
        }

        throw new Exception('Getting undefined attribute.');
    }

    public function __set($attribute, $value)
    {
        if (array_key_exists($attribute, $this->attributes)) {
            $this->attributes[$attribute] = $value;
            return;
        }

        throw new Exception('Setting undefined attribute.');
    }

    abstract public function validate();
}