<?php

namespace tweeter;

use PDO;

require_once $_SERVER['DOCUMENT_ROOT']."/../config/db_config.php";

class Database
{
    static $_pdo;

    static function get_pdo() {
        if (!isset($_pdo)) {
            $_pdo = new PDO("mysql:host=".DB_Config::DATABASE_HOST.";dbname=".DB_Config::DATABASE_NAME.";charset=utf8mb4",
                DB_Config::DATABASE_USER,
                DB_Config::DATABASE_PASSWORD,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT => false
                )
            );
        }

        return $_pdo;
    }
}
