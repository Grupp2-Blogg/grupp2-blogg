<?php

declare(strict_types=1);

function db_get_all_userinfo(object $pdo, int $id)
{

    try {

        $query = "SELECT * FROM users WHERE id = :id;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {

            return null;
        } else {
            return $user;
        }
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
