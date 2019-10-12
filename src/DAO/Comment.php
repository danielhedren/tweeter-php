<?php

namespace tweeter\DAO;

use PDO;
use tweeter\Database;

class Comment
{
    private int $id;
    public int $user_id;
    public int $parent_id;
    public string $content;

    public static function fetch($id): ?Comment
    {
        $stmt = Database::get_pdo()->prepare("SELECT * FROM Comment WHERE id = :id;");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $comment = $stmt->fetchObject(self::class);
        if (!$comment) return null;

        return $comment;
    }

    public function save(): void
    {
        if (isset($this->id)) {
            $stmt = Database::get_pdo()->prepare("UPDATE Comment SET user_id = :user_id, parent_id = :parent_id, content = :content WHERE id = :id;");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        } else {
            $stmt = Database::get_pdo()->prepare("INSERT INTO Comment (user_id, parent_id, content) VALUES (:user_id, :parent_id, :content);");
        }
        $stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_INT);
        $stmt->bindParam(":parent_id", $this->parent_id, PDO::PARAM_INT);
        $stmt->bindParam(":content", $this->content, PDO::PARAM_STR);

        // TODO: Handle unique constraint exceptions
        $stmt->execute();
    }

    public static function fetch_chronological($num, $page)
    {
        $stmt = Database::get_pdo()->prepare("SELECT * FROM Comment ORDER BY date DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(":limit", $num, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $page * $num, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);

        return $result;
    }
}