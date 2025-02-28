<?php
require_once '../app/config/session_config.php';

if (!isset($_SESSION['user'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $username = trim($_POST['username']);
        $pwd = trim($_POST['pwd']);

        try {

            require_once '../app/config/dboconn.php';
            require_once '../app/models/login_model.php';
            require_once '../app/controllers/login_contr.php';

            $errors = [];


            if (!is_input_set($username, $pwd)) {
                $errors["no_input"] = "Fill in all required fields!";
                $_SESSION['errors_login'] = $errors;

                header("Location: ../public/login.php");
                exit;
            }

            $user = authorize_login($pdo, $username, $pwd);

            if (!$user) {

                $errors["invalid_login"] = "Incorrect username or password";
                $_SESSION['errors_login'] = $errors;

                header("Location: ../public/login.php");
                exit;
            }

            $_SESSION['user'] = ["id" => $user["id"], "username" => $user["username"]];
            header("Location: ./profile_details.inc.php?login=success");
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
    }
} else {
    header("Location: ../public/index.php");
    exit;
}
