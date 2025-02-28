<?php

declare(strict_types=1);

function check_edit_mode()
{
    if (isset($_SESSION['user'])) {


        if (isset($_SESSION['enter-edit'])) {

            require_once '../includes/editform.inc.php';
            unset($_SESSION['enter-edit']);
        } else {
            require_once '../includes/account_info.inc.php';
        }

    }
}

function check_profile_update_errors()
{

    if (isset($_SESSION['errors_profile'])) {

        $errors = $_SESSION['errors_profile'];

        echo "<br>";

        foreach ($errors as $error) {
            echo '<p class="error-msg">' . $error  . '</p>';
        }

        unset($_SESSION['errors_profile']);
    }
}
