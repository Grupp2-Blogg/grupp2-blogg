<?php

declare(strict_types=1);


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


function is_input_set (string $username, string $pwd, string $email) {

    if (empty($username) || empty($pwd) || empty($email)) {
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