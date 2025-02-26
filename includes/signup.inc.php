<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $pwd = $_POST['pwd'];
    $email = $_POST['email'];

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $birthyear = $_POST['birthyear'];

    try {

        require_once './dboconn.inc.php';
        require_once './signup_model.inc.php';
        require_once './signup_contr.inc.php';

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

        require_once './session_config.php';

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

            header("Location: ../signup.php");
            exit;
        } else {
            create_new_user($pdo, $username, $pwd, $email, $firstname, $lastname, $gender, $birthyear);
            header("Location: ../login.php?signup=success");

            $pdo = null;
            $stmt = null;

            die();
        }
        // die();
    } catch (PDOException $e) {
        die("Query failed: " .  $e->getMessage());
    }
}
