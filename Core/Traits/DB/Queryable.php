<?php

namespace Core\Traits\DB;

use Core\Db;
use PDO;

trait Queryable
{

    static protected string|null $tablename = "users";



    static protected string $query = "";

    protected array  $commands =[];

    public static function create(array $fields)
    {
        $vars = static::prepareQueryVars($fields);
        $query = "INSERT INTO " . static::$tablename . "({$vars['keys']}) VALUES ({$vars['placeholders']})";
        $query = Db::connect()->prepare($query);
        $query->execute($fields);

        return (int)Db::connect()->lastInsertId();
    }

    public static function update(array $fields, int $id)
    {
        $vars = static::buildPlaceholders($fields);
        $query = "UPDATE " . static::$tablename . " SET " . $vars . " WHERE id=:id";
        $stmt = Db::connect()->prepare($query);

        foreach ($fields as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        $stmt->bindValue('id',$id, PDO::PARAM_INT);
        $stmt->execute();

        return static::find($id);
    }

    protected static function buildPlaceholders(array $fields): string
    {
        $ps = [];

        foreach ($fields as $key => $value) {
            $ps[] = " {$key}=:{$key}";
        }

        return implode(', ', $ps);
    }

    protected static function prepareQueryVars(array $fields):array
    {
        $keys = array_keys($fields);
        $placeholders = preg_filter('/^/', ':', $keys);

        return [
            'keys' => implode(', ', $keys),
            'placeholders' => implode(', ', $placeholders)
        ];
    }

    public static function all(): static
    {
        static::$query = "SELECT * FROM " . static::$tablename;
        $obj = new static();
        $obj->commands[] = 'all';
        d($obj);

        return $obj;
    }
    public static function select()
    {
        static::$query = "SELECT * FROM " . static::$tablename;
        $query = Db::connect()->prepare(static::$query);
        $query->execute();
        $data = $query->fetchall();
        return $data;
    }

    public  static function where($column, $sign, $value)
    {
        switch ($sign){
            case "<":
                $query = "SELECT * FROM " . static::$tablename . " WHERE $column<:value";
                break;
            case "=":
                $query = "SELECT * FROM " . static::$tablename . " WHERE $column=:value";
                break;
            case ">":
                $query = "SELECT * FROM " . static::$tablename . " WHERE $column>:value";
                break;
            default:
                dd("Invalid sign. < > = accepted only");
        }
//        $query = "SELECT * FROM " . static::$tablename . " WHERE $column=:value";
        $query = Db::connect()->prepare($query);
        $query->bindParam ('value', $value);
        $query->execute();
        $data = $query->fetchall();
        return $data;

    }

    public static function find(int $id)
    {
        $query = "SELECT * FROM " . static::$tablename . " WHERE id=:id";
        $query = Db::connect()->prepare($query);
        $query->bindParam('id', $id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject(static::class);
    }

    public static function delete(int $id)
    {
        $query = "DELETE FROM " . static::$tablename . " WHERE id=:id";

        $query = Db::connect()->prepare($query);
        $query->bindParam('id', $id, PDO::PARAM_INT);
        $query->execute();

        dd("id $id deleted");
    }

    public static function findBy(string $column, $value)
    {
        $query = "SELECT * FROM " . static::$tablename . " WHERE {$column}=:{$column}";

        $query = Db::connect()->prepare($query);
        $query->bindParam($column, $value);
        $query->execute();

        return $query->fetchObject(static::class);
    }

    public function orderBy($column, $direction = 'ASC'):static
    {
        if ($this->allowMethod(['all', 'select'])){
            $this->commands[] = 'order';
            static::$query .=" ORDER BY {$column} {$direction}";
        }
        return $this;
    }

    public function allowMethod(array $allowedMethods):bool
    {
        foreach ($allowedMethods as $method){
            if (in_array($method, $this->commands)){
                return true;
            }
        }
        return false;
    }

    public function get()
    {

        return Db::connect()->query(static::$query)->fetchAll(PDO::FETCH_CLASS, static::class);
    }
}