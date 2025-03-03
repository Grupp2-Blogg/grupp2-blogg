<?php

declare(strict_types=1);

function handle_user_login(object $pdo, string $username, string $pwd)
{

    $user = authorize_login($pdo, $username, $pwd);

    if (!$user) {

        $errors["invalid_login"] = "Inkorrekt användarnamn eller lösenord";
        $_SESSION['errors_login'] = $errors;

        header("Location: ../public/login.php");
        exit;
    } else {

        $_SESSION['user'] = $user;
        $_SESSION['recent_login'] = "true";
        header("Location: ../public/index.php");
        $stmt = null;
        $pdo = null;
        die();
    }
}

function authorize_login(object $pdo, string $username, string $pwd)
{

    $user = db_get_user_by_username($pdo, $username, $pwd);

    if (empty($user)) {
        return null;
    } else {
        return $user;
    }
}

function is_input_set(string $username, string $pwd)
{

    if (empty($username) || empty($pwd)) {
        return false;
    } else {
        return true;
    }
}
