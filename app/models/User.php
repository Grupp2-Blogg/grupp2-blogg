<?php

declare(strict_types=1);

class User
{

    private PDO $pdo;
    private int $id;
    private string $username;
    private string $email;
    private string $hash_pwd;
    private ?string $firstname;
    private ?string $lastname;
    private ?string $gender;
    private string|int|null $birthyear;


    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(string $username, string $pwd, string $email, ?string $firstname = NULL, ?string $lastname = NULL, ?string $gender = NULL, ?int $birthyear = NULL)
    {

        try {

            $hash_pwd = password_hash($pwd, PASSWORD_DEFAULT);

            $query = "INSERT 
                        INTO 
                            users (username, email, hash_pwd, firstname, lastname, gender, birthyear)
                        VALUES 
                            (:username, :email, :hash_pwd, :firstname, :lastname, :gender, :birthyear);";

            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':hash_pwd', $hash_pwd);
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

            return true;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function existsByUsername(string $username): bool
    {
        try {
            $query = "SELECT 
                            u.username 
                        FROM 
                            users as u 
                        WHERE 
                            username = :username;";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $user_result = $stmt->fetch(PDO::FETCH_ASSOC);

            return (bool)$user_result;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function existsByEmail(string $email): bool
    {
        try {
            $query = "SELECT 
                            u.email 
                        FROM 
                            users as u 
                        WHERE 
                            email = :email;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $user_result = $stmt->fetch(PDO::FETCH_ASSOC);

            return (bool)$user_result;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
}
