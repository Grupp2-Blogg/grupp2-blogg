<?php

declare(strict_types=1);

function check_new_user() {

    if (isset($_GET['signup'])) {

        if ($_GET['signup'] == 'success') {
            echo "<h2>Registration Complete!</h2><br><br>";
            unset($_GET['signup']);
        }
    }
}

function check_login_fail() {

    if (isset($_SESSION['errors_login'])) {

        $errors = $_SESSION['errors_login'];

        echo "<br>";

        foreach ($errors as $error) {
            echo '<p class="error-msg">' . $error  . '</p>';
        }

        unset($_SESSION['errors_login']);

    }

}