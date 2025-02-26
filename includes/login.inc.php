<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $pwd = $_POST['pwd'];

    try {

        require_once './dboconn.inc.php';
        require_once './login_model.inc.php';
        require_once './login_contr.inc.php';

        $errors = [];

        require_once './session_config.php';
        
        if (!is_input_set($username, $pwd)) {
            $errors["no_input"] = "Fill in all required fields!";
            $_SESSION['errors_login'] = $errors;

            header("Location: ../login.php?login=fail");
            exit;
        }

        $user = is_correct_login($pdo, $username, $pwd);

        if (empty($user) || !isset($user) || $user === NULL) {

            $errors["invalid_login"] = "Incorrect user or password";
            $_SESSION['errors_login'] = $errors;

            header("Location: ../login.php?login=fail");
            exit;
        }

        $_SESSION["user"] = ["id" => $user["id"], "username" => $user["username"]];
        header("Location: ../profile.php?login=success");

        $stmt = null;
        $pdo = null;

        die();
    } catch (PDOException $e) {
        die("Query failed: " .  $e->getMessage());
    }
}
