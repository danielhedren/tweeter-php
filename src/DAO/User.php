<?php

require_once "../../src/database.php";

class User
{
    public $id;
    public $email;
    public $displayname;
    public $password;
    public $date;

    public static function fetch($user_id) {
        $stmt = Database::get_pdo()->prepare("SELECT * FROM User WHERE id = :id");
        $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_CLASS, "User");
        return $user;
    }
}