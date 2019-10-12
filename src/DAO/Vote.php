<?php

namespace tweeter\DAO;

use PDO;
use tweeter\Database;

class Vote
{
    private int $id;
    public int $user_id;
    public int $comment_id;

    public static function fetch($id): ?Vote
    {
        $stmt = Database::get_pdo()->prepare("SELECT * FROM Vote WHERE id = :id;");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $vote = $stmt->fetchObject(self::class);
        if (!$vote) return null;

        return $vote;
    }

    public function save(): void
    {
        if (isset($this->id)) {
            $stmt = Database::get_pdo()->prepare("UPDATE Vote SET user_id = :user_id, comment_id = :comment_id WHERE id = :id;");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        } else {
            $stmt = Database::get_pdo()->prepare("INSERT INTO Vote (user_id, comment_id) VALUES (:user_id, :comment_id);");
        }
        $stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_INT);
        $stmt->bindParam(":comment_id", $this->comment_id, PDO::PARAM_INT);

        // TODO: Handle unique constraint exceptions
        $stmt->execute();
    }
}