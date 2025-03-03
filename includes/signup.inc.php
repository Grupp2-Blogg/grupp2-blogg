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

        if (!is_input_set($username, $pwd, $pwd_repeat, $email)) {
            $errors["no_input"] = "Fyll i de obligatoriska fälten!";
        }
        if ($pwd !== $pwd_repeat) {

            $errors['pw_mismatch'] = "Lösenorden matchar inte, försök igen";
        }
        if (!is_valid_email($email)) {
            $errors["invalid_email"] = "Ogiltigt format på email!";
        }
        if (!is_new_username($pdo, $username)) {
            $errors["username_taken"] = "Användarnamnet är redan taget!";
        }
        if (!is_new_email($pdo, $email)) {
            $errors["email_taken"] = "Email-adressen är redan tagen!";
        }
        if (!isset($_POST['tc'])) {
            $errors["tc_nocheck"] = "Du behöver acceptera villkoren!";
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
