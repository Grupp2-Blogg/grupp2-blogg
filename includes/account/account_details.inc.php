<?php
// require_once '../../app/config/session_config.php';
// $currentDir = getcwd();
// echo $currentDir;
// require_once '../../app/config/session_config.php';


if (!isset($_SESSION['user'])) {
    header("Location: ./login.php");
    exit;
}


try {

    require_once '../app/config/dboconn.php';
    require_once '../app/models/account_details_model.php';
    require_once '../app/controllers/account_details_contr.php';




    // Om användaren tryckt "Redigera" knappen, sätt session variabel.
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['account-action'])) {

        $get_allowedValues = ['account-enter-edit', 'pw-enter-confirm-old'];

        if (in_array($_GET['account-action'], $get_allowedValues, true)) {

            $_SESSION['enter-edit'] = $_GET['account-action'];
            // header("Location: .././public/account_details.php");
            header("Location: ./account_details.php");

            exit;
        }
    }


    $id = $_SESSION['user']['id'];
    $user = get_all_userinfo_byID($pdo, $id);
    $errors = [];

    if (!$user) {

        $errors["invalid_fetch"] = "Couldn't retrieve user data";
        $_SESSION['errors_account'] = $errors;

        header("Location: ./login.php");
        exit;
    } else {

        unset($_SESSION['user']);

        $_SESSION['user'] = $user;
    }



    //     // Om användaren tryckt "Spara ändringar" knappen. Kör insert till databas(efter massa validering)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['account-action'])) {

        $post_allowedValues = ['account-update', 'pw-confirm-old', 'pw-update'];

        if (in_array($_POST['account-action'], $post_allowedValues, true)) {


            if ($_POST['account-action'] === 'pw-confirm-old') {

                $old_pwd = trim($_POST['pw-confirm-old']);

                if (!is_pwd_set($old_pwd)) {

                    $errors['no_input'] = "No password entered";
                }
                $user = confirm_pwd($pdo, $id, $old_pwd);
                if ($user === false) {

                    $errors['errors_confirm'] = "Incorrect password";
                } elseif ($user === NULL) {

                    $errors["invalid_fetch"] = "Couldn't retrieve user data";
                }

                if (!empty($errors)) {

                    $_SESSION['errors_account'] = $errors;
                    header("Location: ./account_details.php");
                    exit;
                } else {

                    unset($user);
                    $_SESSION['enter-edit'] = 'pw-enter-edit';
                    header("Location: ./account_details.php");
                    exit;
                }
            }
            if ($_POST['account-action'] === 'pw-update') {

                $new_pwd = trim($_POST['pw-update']);
                $repeat_pwd = trim($_POST['pw-update-repeat']);

                if (!is_both_pwd_set($new_pwd, $repeat_pwd)) {

                    $errors['errors_account'] = "Fill in both fields!";
                } elseif ($new_pwd !== $repeat_pwd) {

                    $errors['errors_account'] = "Passwords do not match, please re-enter";
                }

                if (!empty($errors)) {

                    $_SESSION['errors_account'] = $errors;
                    header("Location: ./account_details.php");
                    exit;
                } else {

                    update_pwd($pdo, $id, $new_pwd);
                    unset($new_pwd);
                    unset($repeat_pwd);
                    $_SESSION['pwd_update_complete'] = "Password updated!";
                    unset($_SESSION['enter-edit']);
                    header("Location: ./account_details.php");
                    $pdo = null;
                    $stmt = null;
                    die();
                }
            }

            if ($_POST['account-action'] === 'account-update') {

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

                    $_SESSION['errors_account'] = $errors;
                    header("Location: ./account_details.php");
                    exit;
                } else {

                    update_user($pdo, $id, $username, $email, $firstname, $lastname, $gender, $birthyear);
                    $updatedUser = get_all_userinfo_byID($pdo, $id);

                    if (!$updatedUser) {
                        $errors["invalid_fetch"] = "Couldn't retrieve user data";
                        $_SESSION['errors_account'] = $errors;
                        header("Location: ./login.php");
                        exit;
                    } else {
                        unset($_SESSION['enter-edit']);
                        $_SESSION['user'] = $updatedUser;
                        header("Location: ./account_details.php");
                        $pdo = null;
                        $stmt = null;
                        die();
                    }
                }
            }
        }
    }


    header("Location: ./account_details.php");
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
