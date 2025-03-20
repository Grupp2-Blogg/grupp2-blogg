<?php

declare(strict_types=1);

function getPostsAndUsersBySearch(PDO $pdo) {

    $searchTerm = "%" . $_POST['search'] . "%";
    $query = "SELECT
                    u.id as user_id,
                    u.username,
                    bp.id as blogpost_id,
                    bp.blogtitle
                FROM
                    users as u,
                LEFT JOIN
                    blogposts as bp ON
                    u.id = pb.user_id
                WHERE
                    u.username LIKE :search OR
                    bp.blogtitle LIKE :search;";

    $stmt = $pdo->prepare($query); 
    $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);

    $stmt->execute(); 
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

   print_r($results); 
}




?>