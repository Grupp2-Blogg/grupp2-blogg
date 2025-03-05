<?php

declare(strict_types=1);
require_once './session_config.php';

// Funktionen kollar vilket läge/format som account_details.php ska visas.
function checkEditMode()
{
    if (!isset($_SESSION['user'])) {

        header("Location: ./login.php");
        exit;
    }

    $allowedValues = ['account-enter-edit', 'pw-enter-confirm-old', 'pw-enter-edit', 'account-enter-delete', 'account-enter-destroy', 'account-destroy'];

    // Hämta 'mode' från GET (om den finns), annars sätt den till standard ('account-info')
    $mode = $_GET['mode'] ?? 'account-info';

    if ($mode !== 'account-enter-destroy' && isset($_SESSION['delete-pw-confirm'])) {
        unset($_SESSION['delete-pw-confirm']);
    }

    if (in_array($mode, $allowedValues, true)) {
        switch ($mode) {
            case 'account-enter-edit':
                require_once './account_form_includes/editform.inc.php';
                break;
            case 'pw-enter-confirm-old':
                require_once './account_form_includes/pw_confirm_old.inc.php';
                break;
            case 'pw-enter-edit':
                require_once './account_form_includes/pw_update.inc.php';
                break;
            case 'account-enter-delete':
                $_SESSION['delete-pw-confirm'] = 'true';
                require_once './account_form_includes/pw_confirm_old.inc.php';
                break;
            case 'account-enter-destroy':
                require_once './account_form_includes/acc_enter_destroy.inc.php';
                break;
            case 'account-destroy':
                $_SESSION['account-deleted'] = 'true';
                header("Location: ./index.php");
                exit;
            default:
                header("Location: ./error.php"); // Felaktigt mode, skicka till error-sida
                exit;
        }
    } else {
        
        require_once './account_form_includes/account_info.inc.php'; // Standardvy om 'mode' saknas eller är ogiltigt
    }

    
}
// Funktionen kollar efter errors vid uppdatering av konto - och visar dem.
function checkAccountUpdateErrors()
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


if (!isset($_SESSION['user'])) {

    header("Location: ./login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Blogg Profil</title>
</head>

<body>
    <main class="page-wrapper">
        <section class="account-container">
            <h2>Personuppgifter</h2>
            <?php
            checkEditMode();
            checkAccountUpdateErrors();

            if (isset($_SESSION['pwd_update_complete'])) {
                echo $_SESSION['pwd_update_complete'];
                unset($_SESSION['pwd_update_complete']);
            }
            echo '<a href="./index.php?logout=true" class="login-btn">Logga ut</a>';
            echo '<a href="./index.php" class="login-btn">Startsida</a>';

            ?>
        </section>
    </main>

</body>

</html>