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


    function delete(int $id) {

        try {
    
            $query = "DELETE FROM 
                            users
                        WHERE id = :id;";
    
            $stmt = $this->pdo->prepare($query);
    
            $stmt->bindParam(':id', $id);
    
            $stmt->execute();
    
            
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    
    }
    
    function updateUserPwd(int $id, string $new_pwd) {
    
        try {
    
            $hash_pwd = password_hash($new_pwd, PASSWORD_DEFAULT);
    
            $query = "UPDATE 
                            users 
                        SET 
                            hash_pwd = :hash_pwd
                        WHERE id = :id;";
    
            $stmt = $this->pdo->prepare($query);
    
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':hash_pwd', $hash_pwd);
    
            $stmt->execute();
    
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    
    }
    
    function getUserByID(int $id, string $old_pwd)
    {
    
        try {
    
            $query = "SELECT 
                            u.hash_pwd 
                        FROM 
                            users as u 
                        WHERE id = :id;";
    
            $stmt = $pdo->prepare($query);
    
            $stmt->bindParam(':id', $id);
    
            $stmt->execute();
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    

            if (!$user) {

                return null;
            } else {

                $isValidPwd = password_verify($pwd, $user['hash_pwd']);
                if ($isValidPwd) {
                    return $user = ["id" => $user["id"], "username" => $user["username"]];
                } else {
                    return null;
                }
            }

            if (!$user) {
    
                return null;
            } else {
    
                return password_verify($old_pwd, $user['hash_pwd']);
            }
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
    
    function updateUserInfo(object $pdo, int $id, string $username, string $email, ?string $firstname = NULL, ?string $lastname = NULL, ?string $gender = NULL, ?int $birthyear = NULL)
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
    
    
    function getAllInfoByID(int $id)
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
    
            $stmt = $this->pdo->prepare($query);
    
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
    
    function db_get_username(object $pdo, string $username)
    {
    
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
    
    function db_get_email(object $pdo, string $email)
    {
    
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
    
#region klara grejer
    public function auth_Login(string $username, string $pwd)
    {

        try {

            $query = "SELECT * FROM users WHERE username = :username;";

            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(":username", $username);

            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {

                return null;
            } else {

                $isValidPwd = password_verify($pwd, $user['hash_pwd']);
                if ($isValidPwd) {
                    return $user = ["id" => $user["id"], "username" => $user["username"]];
                } else {
                    return null;
                }
            }
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
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
            return (int)$this->pdo->lastInsertId();
            // return true;
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
    #endregion
}
