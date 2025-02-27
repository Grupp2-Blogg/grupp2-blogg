<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $pwd = $_POST['pwd'];

    try {

        require_once '../app/config/dboconn.php';
        require_once '../app/models/login_model.php';
        require_once '../app/controllers/login_contr.php';

        $errors = [];

        require_once '../app/config/session_config.php';
        
        if (!is_input_set($username, $pwd)) {
            $errors["no_input"] = "Fill in all required fields!";
            $_SESSION['errors_login'] = $errors;

            header("Location: ../public/login.php");
            exit;
        }

        $user = authorize_login($pdo, $username, $pwd);

        if (empty($user) || !isset($user) || $user === NULL) {

            $errors["invalid_login"] = "Incorrect username or password";
            $_SESSION['errors_login'] = $errors;

            header("Location: ../public/login.php");
            exit;
        }

        $_SESSION["user"] = ["id" => $user["id"], "username" => $user["username"]];
        header("Location: ./profile.inc.php?login=success");

        $stmt = null;
        $pdo = null;

        die();
    } catch (PDOException $e) {
        die("Query failed: " .  $e->getMessage());
    }
}
