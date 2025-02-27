<?php

if (!isset($_SESSION['user'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $username = trim($_POST['username']);
        $pwd = trim($_POST['pwd']);
        $email = trim($_POST['email']);

        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $gender = trim($_POST['gender']);
        $birthyear = trim($_POST['birthyear']);

        try {

            require_once '../app/config/dboconn.php';
            require_once '../app/models/signup_model.php';
            require_once '../app/controllers/signup_contr.php';

            // ERROR HANDLERS
            $errors = [];

            if (!is_firstname_set($firstname)) {
                $firstname = null;
            }
            if (!is_lastname_set($lastname)) {
                $lastname = null;
            }
            if (!is_birthyear_set($birthyear)) {
                $birthyear = null;
            } else {
                $birthyear = (int)$birthyear;
            }
            if (!is_gender_set($gender)) {
                $gender = null;
            }

            if (!is_input_set($username, $pwd, $email)) {
                $errors["no_input"] = "Fill in all required fields!";
            }
            if (!is_valid_email($email)) {
                $errors["invalid_email"] = "Invalid email format!";
            }
            if (!is_new_username($pdo, $username)) {
                $errors["username_taken"] = "That username is already taken!";
            }
            if (!is_new_email($pdo, $email)) {
                $errors["email_taken"] = "That email is already taken!";
            }
            if (!isset($_POST['tc'])) {
                $errors["tc_nocheck"] = "You need to accept the Terms & Service!";
            }

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
                header("Location: ../public/login.php?signup=success");

                $pdo = null;
                $stmt = null;

                die();
            }
            
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
