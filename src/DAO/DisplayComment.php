<?php

namespace tweeter\DAO;

use PDO;
use tweeter\Database;

class DisplayComment
{
    private int $id;
    public int $user_id;
    public int $parent_id;
    public string $content;
    public string $displayname;

    public function __construct()
    {
        $this->content = htmlspecialchars($this->content);
        $this->displayname = htmlspecialchars($this->displayname);
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public static function fetch($id): ?DisplayComment
    {
        $stmt = Database::get_pdo()->prepare("SELECT c.*, u.displayname FROM Comment c JOIN User u ON c.user_id = u.id WHERE c.id = :id;");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $comment = $stmt->fetchObject(self::class);
        if (!$comment) return null;

        return $comment;
    }

    public static function fetch_chronological($num, $page)
    {
        $stmt = Database::get_pdo()->prepare("SELECT c.*, u.displayname FROM Comment c JOIN User u ON c.user_id = u.id ORDER BY c.date DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(":limit", $num, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $page * $num, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);

        return $result;
    }
}