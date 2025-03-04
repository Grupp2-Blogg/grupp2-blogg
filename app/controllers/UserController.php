<?php

declare(strict_types=1);
require_once '../config/session_config.php';
require_once '../models/User.php';


class UserController
{

    private PDO $pdo;
    private User $user;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->user = new User($pdo);
    }


public function login() {


if (isset($_SESSION['user'])) {

    header("Location: ../../public/index.php");
    exit;

}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $pwd = trim($_POST['pwd']);

    try {


        $errors = [];

        if (!is_input_set($username, $pwd)) {
            $errors["no_input"] = "Fyll i de obligatoriska fälten!";
            $_SESSION['errors_login'] = $errors;

            header("Location: ../../public/login.php");
            exit;
        }

        handleLogin($this->pdo, $username, $pwd);
        

    } catch (PDOException $e) {
        $pdo = null;
        die("Query failed: " .  $e->getMessage());
        // error_log($e->getMessage(), 3, 'C:/xampp/htdocs/myCode/grupp2-blogg/error.log');
        // header("Location: ../public/error.php");
        exit;
    }
} else {
    header("Location:"Location: ../../public/index.php");
    exit;
}}

    













    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username']);
            $pwd = trim($_POST['pwd']);
            $pwd_repeat = trim($_POST['pwd-repeat']);
            $email = trim($_POST['email']);

            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            $gender = trim($_POST['gender']);
            $birthyear = trim($_POST['birthyear']);

            try {

                require_once '../app/config/dboconn.php';

                // ERROR- OCH VALIDERINGSFUNKTIONER
                $errors = $this->validateReqFields($username, $pwd, $pwd_repeat, $email);

                if (!empty($errors)) {
                    $_SESSION['errors_signup'] = $errors;
                    header("Location: ../../public/signup.php");
                    exit;
                } else {
                    $user->create($pdo, $username, $pwd, $email, $firstname, $lastname, $gender, $birthyear);
                    $_SESSION['signup'] = 'success';
                    header("Location: ../../public/login.php");
                    $pdo = null;
                    die();
                }
            } catch (PDOException $e) {
                die("Query failed: " .  $e->getMessage());
            }
        }
    }

    private function validateReqFields(string $username, string $pwd, string $pwd_repeat, string $email)
    {

        $errors = [];

        if (empty($username) || empty($pwd) || empty($email) || empty($pwd_repeat)) {
            $errors["no_input"] = "Fyll i de obligatoriska fälten!";
        }
        if ($pwd !== $pwd_repeat) {

            $errors['pw_mismatch'] = "Lösenorden matchar inte, försök igen";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["invalid_email"] = "Ogiltigt format på email!";
        }
        if ($this->user->existsByUsername($username)) {
            $errors["username_taken"] = "Användarnamnet är redan taget!";
        }
        if ($this->user->existsByEmail($email)) {
            $errors["email_taken"] = "Email-adressen är redan tagen!";
        }
        if (!isset($_POST['tc'])) {
            $errors["tc_nocheck"] = "Du behöver acceptera villkoren!";
        }

        return $errors;
    }
   
}
