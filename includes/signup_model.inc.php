<?php

declare(strict_types=1);

function db_create_user (object $pdo, string $username, string $pwd, string $email, ?string $firstname = NULL, ?string $lastname = NULL, ?string $gender = NULL, ?int $birthyear = NULL) {

    try {
        
        $hash = password_hash($pwd, PASSWORD_DEFAULT);

        $query = "INSERT 
                    INTO 
                        users (username, email, hash, firstname, lastname, gender, birthyear)
                    VALUES 
                        (:username, :email, :hash, :firstname, :lastname, :gender, :birthyear);";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':email', $email);
        
        if ($gender === null) {
            $stmt->bindValue(':gender', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        }

        if ($firstname === null) {
            $stmt->bindValue(':firstname', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':firstname', $firstname);
        }

        if ($lastname === null) {
            $stmt->bindValue(':lastname', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':lastname', $lastname);
        }

        if ($birthyear === null) {
            $stmt->bindValue(':birthyear', $birthyear, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':birthyear', $birthyear);
        }

        $stmt->execute();

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

}

function db_get_username (object $pdo, string $username) {

    try {

        $query = "SELECT 
                        u.username 
                    FROM 
                        users as u 
                    WHERE 
                        username = :username;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":username", $username);

        $stmt->execute();

        $user_result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user_result;

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

}

function db_get_email (object $pdo, string $email) {

    try {

        $query = "SELECT 
                        u.email 
                    FROM 
                        users as u 
                    WHERE 
                        email = :email;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":email", $email);

        $stmt->execute();

        $user_result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user_result;

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
    
}