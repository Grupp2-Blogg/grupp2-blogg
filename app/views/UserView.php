<?php

declare(strict_types=1);


// Funktionen kollar efter errors vid uppdatering av konto - och visar dem.
function checkAccUpdateErrors()
{

    if (isset($_SESSION['errors_account'])) {

        $errors = $_SESSION['errors_account'];
        unset($_SESSION['errors_account']);

        echo "<br>";

        foreach ($errors as $error) {
            echo '<p class="error-msg">' . $error  . '</p>';
        }
    }
}



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
