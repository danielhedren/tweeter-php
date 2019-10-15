<?php


namespace tweeter\DAO;

use tweeter\Database;

class Vote
{
    public static function create(int $user_id, int $comment_id): void
    {
        $stmt = Database::get_pdo()->prepare("INSERT INTO Vote() VALUES ();");
        $stmt->execute();

        $stmt = Database::get_pdo()->prepare("INSERT INTO VoteTernary (vote_id, user_id, comment_id) VALUES (LAST_INSERT_ID(), :user_id, :comment_id);");
        $stmt->bindValue(":user_id", $user_id);
        $stmt->bindValue(":comment_id", $comment_id);
        $stmt->execute();
    }
}
