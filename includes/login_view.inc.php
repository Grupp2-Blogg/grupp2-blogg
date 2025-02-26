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