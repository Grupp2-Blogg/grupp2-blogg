<?php

declare(strict_types=1);


function db_get_user_by_username(object $pdo, string $username, string $pwd)
{

    try {

        $query = "SELECT
                    id, username, hash_pwd
                    FROM 
                        users 
                    WHERE 
                        username = :username;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":username", $username);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {

            return null;
        } else {

            $isValidPwd = password_verify($pwd, $user['hash_pwd']);

            if ($isValidPwd) {
                unset($user['hash_pwd']);
                return $user;
            } else {
                return null;
            }
        }
    } catch (PDOException $e) {
        $stmt = null;
        $pdo = null;
        die("Query failed: " . $e->getMessage());
        // error_log($e->getMessage(), 3, 'C:/xampp/htdocs/myCode/grupp2-blogg/error.log');
        // header("Location: ../../public/error.php");
        exit;
    }
}
