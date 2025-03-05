<?php
require_once '../app/config/session_config.php';
require_once '../app/controllers/UserController.php';
// require_once '../app/models/User.php';


try {

    require_once '../app/config/dboconn.php';

    $userController = new UserController($pdo);
    $id;

    if (!isset($_SESSION['user'])) {

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['account-action'])) {

                $_SESSION['editmode'] = $_GET['account-action'];
                
                if ($_GET['account-action'] === 'login-redirect') {
                    
                    header("Location: ./login.php");
                    exit;
                }
                if ($_GET['account-action'] === 'register-redirect') {

                    header("Location: ./signup.php");
                    exit;
                }
                if ($_GET['account-action'] === 'account-details-redirect') {

                    
                    header("Location: ./account_details.php");
                    exit;
                }
                if ($_GET['account-action'] === 'account-logout') {

                    header("Location: ./index.php?logout=true");
                    exit;
                }
            }
            
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['account-action'])) {

                if ($_POST['account-action'] === 'account-login') {

                    $userController->login();
                }
                if ($_POST['account-action'] === 'account-register') {

                    $userController->register();
                }
            }
        } else {
            header("Location: ./index.php");
            exit;
        }
    } else {

        $id = $_SESSION['user']['id'];
    }

    $user = $userController->getUserInfo($id);
    $errors = [];

    if (!$user) {

        $errors["invalid_fetch"] = "Kunde inte hämta användare";
        $_SESSION['errors_account'] = $errors;

        header("Location: ./login.php");
        exit;
    } else {

        $_SESSION['user'] = $user;
    }

    // GET CHECKS OCH ROUTING
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        if (isset($_GET['account-action'])) {

            $GET_allowedValues = ['account-enter-edit', 'pw-enter-confirm-old', 'account-enter-delete', 'account-destroy'];

            if (in_array($_GET['account-action'], $GET_allowedValues, true)) {

                switch ($_GET['account-action']) {

                    case 'account-enter-edit':
                        require_once '.././includes/account/editform.inc.php';
                        break;
                    case 'pw-enter-confirm-old':
                        require_once '.././includes/account/pw_confirm_old.inc.php';
                        break;
                    case 'account-enter-delete':
                        $_SESSION['delete-pw-confirm'] = 'true';
                        require_once '.././includes/account/pw_confirm_old.inc.php';
                        break;
                    case 'account-destroy':
                        $userController->handleDelete($id);
                        $_SESSION['account-deleted'] = 'true';
                        header("Location: ./index.php");
                        exit;
                    default:
                        header("Location: ./index.php");
                        break;
                }
            }
            header("Location: ./account_details.php");
            exit;
        }
    }

    


    // POST CHECKS OCH ROUTING
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['account-action'])) {

            $POST_allowedValues = ['account-update', 'pw-confirm-old', 'pw-update', 'pw-enter-edit', 'account-enter-delete'];

            if (in_array($_POST['account-action'], $POST_allowedValues, true)) {

                switch ($_POST['account-action']) {

                    case 'pw-enter-edit':
                        require_once '.././includes/account/pw_update.inc.php';
                        break;
                    case 'account-enter-delete':
                        $_SESSION['delete-pw-confirm'] = 'true';
                        require_once '.././includes/account/pw_confirm_old.inc.php';
                        break;
                    case 'pw-confirm-old':
                        $userController->handlePwdConfirm($id);
                        break;
                    case 'pw-update':
                        $userController->handlePwdUpdate($id);
                        break;
                    case 'account-update':
                        $userController->handleAccInfoUpdate($id);
                        break;
                }
            }
        }
    }


    header("Location: ./account_details.php");
    // $pdo = null;
    exit;
} catch (PDOException $e) {
    // $stmt = null;
    // $pdo = null;
    die("Query failed: " .  $e->getMessage());

    // error_log($e->getMessage(), 3, 'C:/xampp/htdocs/myCode/grupp2-blogg/error.log');
    // header("Location: ../public/error.php");
    exit;
}
