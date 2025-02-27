<?php

declare(strict_types=1);

function check_signup_errors()
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

function populate_year_select_options(int $yearsback)
{

    $currentYear = date("Y");
    $earliestYear = $currentYear - $yearsback;

    for ($i = $currentYear; $i >= $earliestYear; $i--) {

        echo "<option value='{$i}'>{$i}</option>";
    }
}
