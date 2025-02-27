<?php
require_once '../app/config/session_config.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}

$id = $_SESSION['user']['id'];

try {

    require_once '../app/config/dboconn.php';
    require_once '../app/models/profile_model.php';
    require_once '../app/controllers/profile_contr.php';

    $errors = [];

    $user = get_all_userinfo_byID($pdo, $id);

    if (!$user) {

        $errors["invalid_fetch"] = "Couldn't retrieve user data";
        $_SESSION['errors_login'] = $errors;

        header("Location: ../public/login.php");
        exit;
    } else {
        $_SESSION['user'] = $user;
    }
    // Om användaren tryckt "Redigera" knappen, sätt session variabel.
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        if (isset($_GET['account-enter-edit'])) {

            $_SESSION['enter-edit'] = 'true';
            $_SESSION['user'] = $user;

            header("Location: ../public/profile.php");
            exit;

        }
    }



    // Om användaren tryckt "Spara ändringar" knappen. Kör insert till databas(efter massa validering)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // FULL VALIDERING HÄR SAMMA SOM I SIGNUP PÅ SAMTLIGA FÄLT. SEDAN INSERT.

        $username = trim($_POST['username']);
        $email = trim($_POST['email']);

        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $gender = trim($_POST['gender']);
        $birthyear = trim($_POST['birthyear']);

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

        if (!is_input_set($username, $email)) {
            $errors["no_input"] = "Fill in all required fields!";
        }
        if (!is_valid_email($email)) {
            $errors["invalid_email"] = "Invalid email format!";
        }
        if ($_SESSION['user']['email'] !== $email) {

            if (!is_new_email($pdo, $email)) {
                $errors["email_taken"] = "That email is already taken!";
            }
        }
        if ($_SESSION['user']['username'] !== $username) {

            if (!is_new_username($pdo, $username)) {
                $errors["username_taken"] = "That username is already taken!";
            }
        }

        if (!empty($errors)) {

            $_SESSION['errors_profile'] = $errors;
            header("Location: ../public/profile.php");
            exit;
        }
        
        update_user($pdo, $id, $username, $email, $firstname, $lastname, $gender, $birthyear);
        $updatedUser = get_all_userinfo_byID($pdo, $id);
        
        if ($updatedUser) {
            unset($_SESSION['enter-edit']);
            $_SESSION['user'] = $updatedUser;
            header("Location: ../public/profile.php");
            $pdo = null;
            $stmt = null;
            die();
        }

        header("Location: ../public/error.php");

        $pdo = null;
        $stmt = null;

        exit;
    }

    header("Location: ../public/profile.php");
    $stmt = null;
    $pdo = null;
    exit;

} catch (PDOException $e) {
    $stmt = null;
    $pdo = null;
    die("Query failed: " .  $e->getMessage());

    // error_log($e->getMessage(), 3, 'C:/xampp/htdocs/myCode/grupp2-blogg/error.log');
    // header("Location: ../public/error.php");
    exit;
}
