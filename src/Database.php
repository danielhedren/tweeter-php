<?php

namespace tweeter;

use PDO;
use tweeter\config\DB_Config;

class Database
{
    private static PDO $_pdo;

    static function get_pdo(): PDO {
        if (!isset(self::$_pdo)) {
            self::$_pdo = new PDO("mysql:host=".DB_Config::DATABASE_HOST.";dbname=".DB_Config::DATABASE_NAME.";charset=utf8mb4",
                DB_Config::DATABASE_USER,
                DB_Config::DATABASE_PASSWORD,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT => false
                )
            );
        }

        return self::$_pdo;
    }
}
