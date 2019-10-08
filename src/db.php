<?php
require_once "../config/db_config.php";

function get_pdo() {
    return new PDO("mysql:host=".DATABASE_HOST.";dbname=".DATABASE_NAME.";charset=utf8mb4",
        DATABASE_USER,
        DATABASE_PASSWORD,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => false
        )
    );
}
