<?php

declare(strict_types=1);


function validate_optional_fields(?string &$firstname, ?string &$lastname, string|int|null &$birthyear, ?string &$gender) {

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

}

function validate_required_fields(object $pdo, string $username, string $pwd, string $pwd_repeat, string $email) {

    $errors = [];

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

    return $errors;
}

function is_firstname_set(string $firstname) {

    if (empty($firstname)) {
        return false;
    } else {
        return true;
    }
}

function is_lastname_set(string $lastname) {

    if (empty($lastname)) {
        return false;
    } else {
        return true;
    }
}

function is_birthyear_set($birthyear) {

    if (empty($birthyear) || $birthyear == "default-year") {
        return false;
    }
    else {
        return true;
    }
}

function is_gender_set(string $gender) {
    if (empty($gender) || $gender === "no-answer") {
        return false;
    } else {
        return true;
    }

}

function create_new_user(object $pdo, string $username, string $pwd, string $email, ?string $firstname = NULL, ?string $lastname = NULL, ?string $gender = NULL, ?int $birthyear = NULL) {

    db_create_user($pdo, $username, $pwd, $email, $firstname,$lastname, $gender,$birthyear);

}

function is_input_set (string $username, string $pwd, string $pwd_repeat, string $email) {

    if (empty($username) || empty($pwd) || empty($email) || empty($pwd_repeat)) {
        return false;
    }
    else {
        return true;
    }
}

function is_valid_email(string $email) {

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    else {
        return false;
    }

}

function is_new_username(object $pdo, string $username) {


    if (empty(db_get_username($pdo, $username))) {
        return true;
    }
    else {
        return false;
    }

}

function is_new_email(object $pdo, string $email) {

    if (empty(db_get_email($pdo, $email))) {
        return true;
    }
    else {
        return false;
    }

}