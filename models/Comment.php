<?php
require_once __DIR__ . '/../config/dboconn.php';

class Comment{
    private $pdo;
    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function commentsPostId($post_id){
        $stmt = $this->pdo->prepare
        ("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id
        WHERE c.post_id = ? ORDER BY c.created_at DESC");
        $stmt->execute([$post_id]);
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    public function addComment($post_id, $user_id, $content){
        $stmt = $this->pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES(?,?,?)");
        return $stmt->execute([$post_id, $user_id, $content]);
    }
}
?>