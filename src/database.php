<?php
require_once "../config/db_config.php";

class Database
{
    static object $_pdo;

    static function get_pdo() {
        if (!isset($_pdo)) {
            $_pdo = new PDO("mysql:host=".DATABASE_HOST.";dbname=".DATABASE_NAME.";charset=utf8mb4",
                DATABASE_USER,
                DATABASE_PASSWORD,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT => false
                )
            );
        }

        return $_pdo;
    }
}
