<?php
require_once __DIR__ . '/../config/dboconn.php';

class Like{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    //Kollar om användarn redan har gillat ett inlägg
    public function hasLiked($user_id, $post_id){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$user_id, $post_id]);
        return $stmt->fetchColumn() > 0;
    }

    //Lägger till eller tar bort en like
    public function toggleLike($user_id,$post_id){
        if($this->hasLiked($user_id, $post_id)){
            $stmt = $this->pdo->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
            $stmt->execute([$user_id, $post_id]);
            return "ogillat";
        }

        else{
            $stmt = $this->pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $post_id]);
            return "gillat";
        }
    }


    //Hämta alla gillningar från inlägget
    public function countLikes($post_id){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
        $stmt->execute([$post_id]);
        return $stmt->fetchColumn();
    }

    }

?>