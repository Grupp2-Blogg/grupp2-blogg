<?php

declare(strict_types=1);

function check_signup_errors() {

    if (isset($_SESSION['errors_signup'])) {

        $errors = $_SESSION['errors_signup'];

        echo "<br>";

        foreach ($errors as $error) {
            echo '<p class="error-msg">' . $error  . '</p>';
        }

        unset($_SESSION['errors_signup']);
    }

    // else if (isset($_GET['signup'])) {

    //     if ($_GET['signup'] == 'success') {
            
    //         echo "<h2>Signup complete!</h2><br>";
    //     }

    // }

}