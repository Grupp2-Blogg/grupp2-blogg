<?php

declare(strict_types=1);

function get_all_userinfo_byID (object $pdo, int $id) {

    $user = db_get_all_userinfo($pdo, $id);

    if (empty($user)) {
        return null;
    }
    else {
        return $user;        
    }


}

