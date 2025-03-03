<?php

declare(strict_types=1);


function db_get_user_by_username(object $pdo, string $username, string $pwd)
{

    try {

        $query = "SELECT * FROM users WHERE username = :username;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":username", $username);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            
            return null;
        }
        else {
            
            $isValidPwd = password_verify($pwd, $user['hash_pwd']);
            if ($isValidPwd) {
                return $user;
            } else {
                return null;
            }
        }


    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
