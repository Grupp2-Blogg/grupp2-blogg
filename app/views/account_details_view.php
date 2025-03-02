<?php
declare(strict_types=1);


function check_edit_mode()
{
    if (!isset($_SESSION['user'])) {

        header("Location: ./login.php");
        exit;
    }

    if (isset($_SESSION['enter-edit'])) {

        $allowedValues = ['account-enter-edit', 'pw-enter-confirm-old', 'pw-enter-edit'];

        if (in_array($_SESSION['enter-edit'], $allowedValues, true)) {
            
            switch($_SESSION['enter-edit']) {
                case 'account-enter-edit':
                    require_once '.././includes/account/editform.inc.php';
                    // unset($_SESSION['enter-edit']);
                    break;
                case 'pw-enter-confirm-old':
                    require_once '.././includes/account/pw_confirm_old.inc.php';
                    // unset($_SESSION['enter-edit']);
                    break;
                case 'pw-enter-edit':
                    require_once '.././includes/account/pw_update.inc.php';
                    // unset($_SESSION['enter-edit']);
                    break;
                default:
                    unset($_SESSION['enter-edit']); break;
                 }
        } else{

            require_once '.public/error.php';

        }
    } else {
        
        require_once '.././includes/account/account_info.inc.php';

    }
}

function check_account_update_errors()
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
