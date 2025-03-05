<?php

declare(strict_types=1);

function checkForSignupErrors()
{

    if (isset($_SESSION['errors_signup'])) {

        $errors = $_SESSION['errors_signup'];

        echo "<br>";

        foreach ($errors as $error) {
            echo '<p class="error-msg">' . $error  . '</p>';
        }

        unset($_SESSION['errors_signup']);
    }
}

function displayYearSelectOptions(int $yearsback)
{

    $currentYear = date("Y");
    $earliestYear = $currentYear - $yearsback;

    for ($i = $currentYear; $i >= $earliestYear; $i--) {

        echo "<option value='{$i}'>{$i}</option>";
    }
}


function checkForNewUser()
{

    if (isset($_SESSION['signup']) && $_SESSION['signup'] === 'success') {

        echo "<h2>Registration Complete!</h2><br><br>";
        unset($_SESSION['signup']);
    }
}

function checkForLoginFail()
{

    if (isset($_SESSION['errors_login'])) {

        $errors = $_SESSION['errors_login'];

        echo "<br>";

        foreach ($errors as $error) {
            echo '<p class="error-msg">' . $error  . '</p>';
        }

        unset($_SESSION['errors_login']);
    }
}
