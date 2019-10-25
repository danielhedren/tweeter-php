<?php

namespace tweeter\DAO;

use PDO;
use tweeter\Database;

class DisplayComment
{
    public int $id;
    public int $user_id;
    public ?int $parent_id;
    public ?string $content;
    public string $displayname;
    public int $votes;

    public function __construct()
    {
        $this->content = htmlspecialchars($this->content);
        $this->displayname = htmlspecialchars($this->displayname);
    }

    public static function fetch($id): ?DisplayComment
    {
        $stmt = Database::get_pdo()->prepare("SELECT c.*, u.displayname, (SELECT COUNT(*) FROM VoteTernary v WHERE v.comment_id = c.id) as votes FROM Comment c JOIN User u ON c.user_id = u.id WHERE c.id = :id;");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $comment = $stmt->fetchObject(self::class);
        if (!$comment) return null;

        return $comment;
    }

    public static function fetch_chronological($num, $page): array
    {
        $stmt = Database::get_pdo()->prepare("SELECT c.*, u.displayname, (SELECT COUNT(*) FROM VoteTernary v WHERE v.comment_id = c.id) as votes FROM Comment c JOIN User u ON c.user_id = u.id WHERE c.parent_id IS NULL ORDER BY c.date DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(":limit", $num, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $page * $num, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);

        return $result;
    }
}