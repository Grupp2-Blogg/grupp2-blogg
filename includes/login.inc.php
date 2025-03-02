<?php
require_once '../app/config/session_config.php';

if (isset($_SESSION['user'])) {

    header("Location: ./index.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $pwd = trim($_POST['pwd']);

    try {

        require_once '../app/config/dboconn.php';
        require_once '../app/models/login_model.php';
        require_once '../app/controllers/login_contr.php';

        $errors = [];


        if (!is_input_set($username, $pwd)) {
            $errors["no_input"] = "Fyll i de obligatoriska fälten!";
            $_SESSION['errors_login'] = $errors;

            header("Location: ../public/login.php");
            exit;
        }

        $user = authorize_login($pdo, $username, $pwd);

        if (!$user) {

            $errors["invalid_login"] = "Inkorrekt användarnamn eller lösenord";
            $_SESSION['errors_login'] = $errors;

            header("Location: ../public/login.php");
            exit;
        }

        $_SESSION['user'] = ["id" => $user["id"], "username" => $user["username"]];
        $_SESSION['recent_login'] = "true";
        header("Location: ../public/index.php");
        // $stmt = null;
        // $pdo = null;
        die();
    } catch (PDOException $e) {
        $stmt = null;
        $pdo = null;
        die("Query failed: " .  $e->getMessage());
        // error_log($e->getMessage(), 3, 'C:/xampp/htdocs/myCode/grupp2-blogg/error.log');
        // header("Location: ../public/error.php");
        exit;
    }
} else {
    header("Location: ../public/index.php");
    exit;
}
