<?php

require_once $_SERVER['DOCUMENT_ROOT']."/../src/database.php";

class User
{
    private $id;
    public $email;
    public $displayname;
    private $password;
    public $date;

    public static function fetch($user_id)
    {
        $stmt = Database::get_pdo()->prepare("SELECT * FROM User WHERE id = :id;");
        $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetchObject(self::class);
        if (!$user) return null;

        return $user;
    }

    public static function fetch_by_email($email)
    {
        $stmt = Database::get_pdo()->prepare("SELECT * FROM User WHERE email = :email;");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetchObject(self::class);
        if (!$user) return null;

        return $user;
    }

    public function save()
    {
        if (!$this->validate_email()) return;

        if (isset($this->id)) {
            $stmt = Database::get_pdo()->prepare("UPDATE User SET email = :email, displayname = :displayname, password = :password WHERE id = :id);");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        } else {
            $stmt = Database::get_pdo()->prepare("INSERT INTO User (email, displayname, password) VALUES (:email, :displayname, :password);");
        }
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->bindParam(":displayname", $this->displayname, PDO::PARAM_STR);
        $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);

        // TODO: Handle unique constraint exceptions
        $stmt->execute();
    }

    public function set_password($password) {
        if (strlen($password) < 8) return false;

        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return true;
    }

    public function verify($password)
    {
        return password_verify($password, $this->password);
    }

    public function validate_email() {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }
}