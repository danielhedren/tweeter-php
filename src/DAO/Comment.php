<?php

require_once $_SERVER['DOCUMENT_ROOT']."/../src/database.php";

class Comment
{
    private $id;
    public $user_id;
    public $parent_id;
    public $content;

    public static function fetch($id) {
        $stmt = Database::get_pdo()->prepare("SELECT * FROM Comment WHERE id = :id;");
        $stmt->bindParam(":id", id, PDO::PARAM_INT);
        $stmt->execute();
        $comment = $stmt->fetchObject(self::class);
        if (!comment) return null;

        return $comment;
    }

    public function save() {
        if (isset($this->id)) {
            $stmt = Database::get_pdo()->prepare("UPDATE Comment SET user_id = :user_id, parent_id = :parent_id, content = :content WHERE id = :id);");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        } else {
            $stmt = Database::get_pdo()->prepare("INSERT INTO Comment (user_id, parent_id, content) VALUES (:user_id, :parent_id, :content);");
        }
        $stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_STR);
        $stmt->bindParam(":parent_id", $this->parent_id, PDO::PARAM_STR);
        $stmt->bindParam(":content", $this->content, PDO::PARAM_STR);

        // TODO: Handle unique constraint exceptions
        $stmt->execute();
    }

    public static function fetch_chronological($num, $page) {
        $stmt = Database::get_pdo()->prepare("SELECT * FROM Comment ORDER BY date DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(":limit", $num);
        $stmt->bindValue(":offset", $page * $num);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_CLASS, "Comment");

        return $result;
    }
}