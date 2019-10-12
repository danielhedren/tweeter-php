<?php

namespace tweeter\DAO;

use tweeter\Database;

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
        $stmt->bindParam(":id", $user_id, \PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetchObject(self::class);
        if (!$user) return null;

        return $user;
    }

    public static function fetch_by_email($email)
    {
        $stmt = Database::get_pdo()->prepare("SELECT * FROM User WHERE email = :email;");
        $stmt->bindParam(":email", $email, \PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetchObject(self::class);
        if (!$user) return null;

        return $user;
    }

    public function save()
    {
        if (!$this->validate_email()) return;

        if (isset($this->id)) {
            $stmt = Database::get_pdo()->prepare("UPDATE User SET email = :email, displayname = :displayname, password = :password WHERE id = :id;");
            $stmt->bindParam(":id", $this->id, \PDO::PARAM_INT);
        } else {
            $stmt = Database::get_pdo()->prepare("INSERT INTO User (email, displayname, password) VALUES (:email, :displayname, :password);");
        }
        $stmt->bindParam(":email", $this->email, \PDO::PARAM_STR);
        $stmt->bindParam(":displayname", $this->displayname, \PDO::PARAM_STR);
        $stmt->bindParam(":password", $this->password, \PDO::PARAM_STR);

        // TODO: Handle unique constraint exceptions
        $stmt->execute();
    }

    public function set_password($password): bool
    {
        if (strlen($password) < 8) return false;

        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return true;
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public function verify($password): bool
    {
        return password_verify($password, $this->password);
    }

    public function validate_email(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }

    public function fetch_comments($num, $page)
    {
        $stmt = Database::get_pdo()->prepare("SELECT * FROM Comment WHERE user_id = :id ORDER BY date DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":limit", $num, \PDO::PARAM_INT);
        $stmt->bindValue(":offset", $page * $num, \PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_CLASS, "Comment");

        return $result;
    }
}