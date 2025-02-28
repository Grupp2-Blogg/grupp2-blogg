<?php

declare(strict_types=1);

function authorize_login(object $pdo, string $username, string $pwd) {

    return db_get_user_by_username($pdo, $username, $pwd);
}

function is_input_set (string $username, string $pwd) {

    if (empty($username) || empty($pwd)) {
        return false;
    }
    else {
        return true;
    }
}

