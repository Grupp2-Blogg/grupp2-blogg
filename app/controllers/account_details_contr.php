<?php

declare(strict_types=1);

function update_user(object $pdo, int $id, string $username, string $email, ?string $firstname = NULL, ?string $lastname = NULL, ?string $gender = NULL, ?int $birthyear = NULL) {

    db_update_user($pdo, $id, $username, $email, $firstname,$lastname, $gender,$birthyear);

}

function update_pwd(object $pdo, int $id, string $new_pwd) {

    db_update_pwd($pdo, $id, $new_pwd);

}

function confirm_pwd(object $pdo, int $id, string $old_pwd) {

    return db_get_user_byID($pdo, $id, $old_pwd);
}

function is_pwd_set(string $old_pwd) {

    if (empty($old_pwd)) {
        return false;
    } else {
        return true;
    }
}

function get_all_userinfo_byID (object $pdo, int $id) {

    return db_get_all_userinfo($pdo, $id);

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

function is_input_set (string $username, string $email) {

    if (empty($username) || empty($email)) {
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

