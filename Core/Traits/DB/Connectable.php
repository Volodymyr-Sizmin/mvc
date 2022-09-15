<?php

namespace Core\Traits\DB;

use Config\Config;
use PDO;

trait Connectable
{
    protected static PDO|null $connect = null;

    public static function connect()
    {
        if (is_null(static::$connect)){
            $dsn = 'mysql:host='. Config::get('db.host') . ';db.mame=' . Config::get('db.database');
            $options = [PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            d($dsn);
            static::$connect = new PDO(
                $dsn,
                Config::get('db.user'),
                Config::get(('db.password')),
                $options
            );
        }
        return static::$connect;
    }

}