<?php

declare(strict_types=1);

require_once '../config/session_config.php';
require_once '../models/UserModel.php';
// require_once './helperFunctions.php';

class LoginController
{

    private User $userModel;
    private PDO $pdo;


    public function __construct(PDO $pdo)
    {

        $this->pdo = $pdo;
        $this->userModel = new User($this->pdo);
    }

    public function loginUser()
    {

        $username = trim($_POST['username']);
        $pwd = trim($_POST['pwd']);

        $errors = [];
        if ((empty($username) || empty($pwd))) {
            $errors['no_input'] = "Fyll i både användarnamn och lösenord!";
            $_SESSION['errors_login'] = $errors;

            header("Location: ../public/login.php");
            exit;
        }

        $user = $this->userModel->auth_Login($username, $pwd);

        if (!$user) {

            $errors["invalid_login"] = "Inkorrekt användarnamn eller lösenord";
            $_SESSION['errors_login'] = $errors;

            header("Location: ../public/login.php");
            exit;
        }

        if (!empty($errors)) {

            $_SESSION['errors_login'] = $errors;

            header("Location: ../public/login.php");
            exit;
        }

        $_SESSION['user'] = $user;
        $_SESSION['recent_login'] = "true";
        header("Location: ../public/index.php");
        // $pdo = null;
        exit;
    }

    public function checkForNewUser()
    {

        if (isset($_SESSION['signup']) && $_SESSION['signup'] === 'success') {

            echo "<h2>Registration Complete!</h2><br><br>";
            unset($_SESSION['signup']);
        }
    }

    public function checkForLoginErrors()
    {

        if (isset($_SESSION['errors_login'])) {

            $errors = $_SESSION['errors_login'];

            echo "<br>";

            foreach ($errors as $error) {
                echo '<p class="error-msg">' . $error  . '</p>';
            }

            unset($_SESSION['errors_login']);
        }
    }
}
