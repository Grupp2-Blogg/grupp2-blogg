<?php

declare(strict_types=1);

require_once './session_config.php';
require_once './userModel.php';
require_once './helperFunctions.php';

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

            header("Location: ./login.php");
            exit;
        }

        $user = $this->userModel->auth_Login($username, $pwd);

        if (!$user) {

            $errors["invalid_login"] = "Inkorrekt användarnamn eller lösenord";
            $_SESSION['errors_login'] = $errors;

            header("Location: ./login.php");
            exit;
        }

        if (!empty($errors)) {

            $_SESSION['errors_login'] = $errors;

            header("Location: ./login.php");
            exit;
        }

        $_SESSION['user'] = $user;
        $_SESSION['recent_login'] = "true";
        header("Location: ./index.php");
        // $pdo = null;
        exit;
    }
}
