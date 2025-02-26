<?php

declare(strict_types=1);

function is_correct_login(object $pdo, string $username, string $pwd) {

    $user = db_get_user_by_username($pdo, $username, $pwd);

    if (empty($user)) {
        return null;
    }
    else {
        return $user;        
    }
}

function is_input_set (string $username, string $pwd) {

    if (empty($username) || empty($pwd)) {
        return false;
    }
    else {
        return true;
    }
}

