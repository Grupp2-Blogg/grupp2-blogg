<?php

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
        require_once '../app/models/signup_model.php';
        require_once '../app/controllers/signup_contr.php';

        // ERROR- OCH VALIDERINGSFUNKTIONER
        validate_optional_fields($firstname, $lastname, $birthyear, $gender);

        $errors = validate_required_fields($pdo, $username, $pwd, $pwd_repeat, $email);

        require_once '../app/config/session_config.php';

        if (!empty($errors)) {

            $_SESSION['errors_signup'] = $errors;

            // $signupData = [
            //     "username" => $username,
            //     "email" => $email,
            //     "firstname" => $firstname,
            //     "lastname" => $lastname,
            //     "gender" => $gender,
            //     "birthyear" => $birthyear
            // ];

            // $_SESSION['signup_data'] = $signupData;

            header("Location: ../public/signup.php");
            exit;
        } else {
            create_new_user($pdo, $username, $pwd, $email, $firstname, $lastname, $gender, $birthyear);
            $_SESSION['signup'] = 'success';
            header("Location: ../public/login.php");

            $pdo = null;
            $stmt = null;
            die();
        }

    } catch (PDOException $e) {
        die("Query failed: " .  $e->getMessage());
    }
}
