<?php
declare(strict_types=1);

// Funktionen kollar vilket läge/format som account_details.php ska visas.
function check_edit_mode()
{
    if (!isset($_SESSION['user'])) {

        header("Location: ./login.php");
        exit;
    }

    if (isset($_SESSION['enter-edit'])) {

        $allowedValues = ['account-enter-edit', 'pw-enter-confirm-old', 'pw-enter-edit', 'account-enter-delete', 'account-enter-destroy', 'account-destroy'];

        if (in_array($_SESSION['enter-edit'], $allowedValues, true)) {
            
            switch($_SESSION['enter-edit']) {

                case 'account-enter-edit':
                    require_once '.././includes/account/editform.inc.php';
                    break;
                case 'pw-enter-confirm-old':
                    require_once '.././includes/account/pw_confirm_old.inc.php';
                    break;
                case 'pw-enter-edit':
                    require_once '.././includes/account/pw_update.inc.php';
                    break;
                case 'account-enter-delete':
                    $_SESSION['delete-pw-confirm'] = 'true';
                    require_once '.././includes/account/pw_confirm_old.inc.php';
                    break;
                case 'account-enter-destroy':
                    require_once '.././includes/account/acc_enter_destroy.inc.php';
                    break;
                case 'account-destroy':
                    $_SESSION['account-deleted'] = 'true';
                    header("Location: ./index.php");
                    exit; 
                default:
                    unset($_SESSION['enter-edit']); 
                    break;
                 }
        } else{

            require_once '.public/error.php';

        }
    } else {
        
        require_once '.././includes/account/account_info.inc.php';

    }
}
// Funktionen kollar efter errors vid uppdatering av konto - och visar dem.
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
