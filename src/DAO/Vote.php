<?php

namespace tweeter\DAO;

use tweeter\Database;

class Vote
{
    private $id;
    public $user_id;
    public $comment_id;

    public static function fetch($id) {
        $stmt = Database::get_pdo()->prepare("SELECT * FROM Vote WHERE id = :id;");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();
        $vote = $stmt->fetchObject(self::class);
        if (!$vote) return null;

        return $vote;
    }
}