<?php

declare(strict_types=1);

function db_update_user(object $pdo, int $id, string $username, string $email, ?string $firstname = NULL, ?string $lastname = NULL, ?string $gender = NULL, ?int $birthyear = NULL)
{

    try {

        $query = "UPDATE users
                    SET username = :username,
                        email = :email,
                        firstname = :firstname,
                        lastname = :lastname,
                        gender = :gender,
                        birthyear = :birthyear
                    WHERE id = :id;";

        $stmt = $pdo->prepare($query);


        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':username', $username);
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


function db_get_all_userinfo(object $pdo, int $id)
{

    try {

        $query = "SELECT 
                    u.id,
                    u.username,
                    u.email,
                    u.firstname,
                    u.lastname,
                    u.gender,
                    u.birthyear,
                    u.created_at 
                    FROM 
                        users as u 
                    WHERE 
                        id = :id;";

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
        $stmt = null;
        $pdo = null;
        die("Query failed: " . $e->getMessage());
        // error_log($e->getMessage(), 3, 'C:/xampp/htdocs/myCode/grupp2-blogg/error.log');
        // header("Location: ../../public/error.php");
        exit;
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

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;

    } catch (PDOException $e) {
        // die("Query failed: " . $e->getMessage());
        error_log($e->getMessage(), 3, 'C:/xampp/htdocs/myCode/grupp2-blogg/error.log');
        header("Location: ../../public/error.php");
        exit;
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

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;

    } catch (PDOException $e) {
        $stmt = null;
        $pdo = null;
        die("Query failed: " . $e->getMessage());
        // error_log($e->getMessage(), 3, 'C:/xampp/htdocs/myCode/grupp2-blogg/error.log');
        // header("Location: ../../public/error.php");
        exit;
    }
    
}