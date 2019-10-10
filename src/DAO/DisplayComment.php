<?php

require_once $_SERVER['DOCUMENT_ROOT']."/../src/database.php";

class DisplayComment
{
    private $id;
    public $user_id;
    public $parent_id;
    public $content;
    public $displayname;

    public function __construct()
    {
        $this->content = htmlspecialchars($this->content);
        $this->displayname = htmlspecialchars($this->displayname);
    }

    public static function fetch($id) {
        $stmt = Database::get_pdo()->prepare("SELECT c.*, u.displayname FROM Comment c JOIN User u ON c.user_id = u.id WHERE id = :id;");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $comment = $stmt->fetchObject(self::class);
        if (!comment) return null;

        return $comment;
    }

    public static function fetch_chronological($num, $page)
    {
        $stmt = Database::get_pdo()->prepare("SELECT c.*, u.displayname FROM Comment c JOIN User u ON c.user_id = u.id ORDER BY c.date DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(":limit", $num, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $page * $num, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_CLASS, "DisplayComment");

        return $result;
    }
}