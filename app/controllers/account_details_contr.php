<?php

declare(strict_types=1);

function handle_account_update(object $pdo, int $id)
{

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
        $errors["no_input"] = "Fyll i de obligatoriska fälten!";
    }
    if (!is_valid_email($email)) {
        $errors["invalid_email"] = "Ogiltigt format på email!";
    }
    if ($_SESSION['user']['email'] !== $email) {

        if (!is_new_email($pdo, $email)) {
            $errors["email_taken"] = "Email-adressen är redan tagen!";
        }
    }
    if ($_SESSION['user']['username'] !== $username) {

        if (!is_new_username($pdo, $username)) {
            $errors["username_taken"] = "Användarnamnet är redan taget!";
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
            $errors["invalid_fetch"] = "Kunde inte hämta användare";
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



function handle_pwd_update(object $pdo, int $id)
{

    $new_pwd = trim($_POST['pw-update']);
    $repeat_pwd = trim($_POST['pw-update-repeat']);

    if (!is_both_pwd_set($new_pwd, $repeat_pwd)) {

        $errors['no_input'] = "Fyll i båda fälten!";
    } elseif ($new_pwd !== $repeat_pwd) {

        $errors['pw_mismatch'] = "Lösenorden matchar inte, försök igen";
    }

    if (!empty($errors)) {

        $_SESSION['errors_account'] = $errors;
        header("Location: ./account_details.php");
        exit;
    } else {

        update_pwd($pdo, $id, $new_pwd);
        unset($new_pwd);
        unset($repeat_pwd);
        $_SESSION['pwd_update_complete'] = "Lösenord uppdaterat!";
        unset($_SESSION['enter-edit']);
        header("Location: ./account_details.php");
        $pdo = null;
        $stmt = null;
        die();
    }
}


function handle_pwd_confirm(object $pdo, int $id)
{

    $old_pwd = trim($_POST['pw-confirm-old']);

    if (!is_pwd_set($old_pwd)) {

        $errors['no_input'] = "Fyll i lösenord";
    }
    $user = confirm_pwd($pdo, $id, $old_pwd);
    if ($user === false) {

        $errors['errors_confirm'] = "Fel lösenord - försök igen";
    } elseif ($user === NULL) {

        $errors["invalid_fetch"] = "Kunde inte hämta användare";
    }

    if (!empty($errors)) {

        $_SESSION['errors_account'] = $errors;
        header("Location: ./account_details.php");
        exit;
    } else {
        
        unset($user);

        if (isset($_SESSION['delete-pw-confirm']) && $_SESSION['delete-pw-confirm'] === 'true') {
            $_SESSION['enter-edit'] = 'account-enter-destroy';
        } else {
            
            $_SESSION['enter-edit'] = 'pw-enter-edit';
        }
        header("Location: ./account_details.php");
        exit;
    }
}

function handle_account_destroy(object $pdo, int $id) {
    db_delete_user($pdo, $id);
}


function update_user(object $pdo, int $id, string $username, string $email, ?string $firstname = NULL, ?string $lastname = NULL, ?string $gender = NULL, ?int $birthyear = NULL)
{

    db_update_user($pdo, $id, $username, $email, $firstname, $lastname, $gender, $birthyear);
}

function update_pwd(object $pdo, int $id, string $new_pwd)
{

    db_update_pwd($pdo, $id, $new_pwd);
}

function confirm_pwd(object $pdo, int $id, string $old_pwd)
{

    return db_get_user_byID($pdo, $id, $old_pwd);
}

function is_both_pwd_set(string $new_pwd, string $repeat_pwd)
{

    if (empty($new_pwd) || empty($repeat_pwd)) {
        return false;
    } else {
        return true;
    }
}

function is_pwd_set(string $old_pwd)
{

    if (empty($old_pwd)) {
        return false;
    } else {
        return true;
    }
}

function get_all_userinfo_byID(object $pdo, int $id)
{

    return db_get_all_userinfo($pdo, $id);
}

function is_firstname_set(string $firstname)
{

    if (empty($firstname)) {
        return false;
    } else {
        return true;
    }
}

function is_lastname_set(string $lastname)
{

    if (empty($lastname)) {
        return false;
    } else {
        return true;
    }
}

function is_birthyear_set($birthyear)
{

    if (empty($birthyear) || $birthyear == "default-year") {
        return false;
    } else {
        return true;
    }
}

function is_gender_set(string $gender)
{
    if (empty($gender) || $gender === "no-answer") {
        return false;
    } else {
        return true;
    }
}

function is_input_set(string $username, string $email)
{

    if (empty($username) || empty($email)) {
        return false;
    } else {
        return true;
    }
}

function is_valid_email(string $email)
{

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function is_new_username(object $pdo, string $username)
{


    if (empty(db_get_username($pdo, $username))) {
        return true;
    } else {
        return false;
    }
}

function is_new_email(object $pdo, string $email)
{

    if (empty(db_get_email($pdo, $email))) {
        return true;
    } else {
        return false;
    }
}
