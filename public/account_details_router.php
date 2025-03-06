<?php
require_once '../config/session_config.php';
require_once '../config/dboconn.php';
require_once '../controllers/AccountDetailsController.php';


if (!isset($_SESSION['user']['id'])) {
    header("Location: ./login.php");
    exit;
}

$id = (int)$_SESSION['user']['id'];
$controller = new AccountDetailsController($pdo);
$user = $controller->getUserInfo($id);

if (!$user) {

    $errors["invalid_fetch"] = "Kunde inte hämta användare";
    $_SESSION['errors_account'] = $errors;

    header("Location: ./login.php");
    exit;
}
$_SESSION['user'] = $user;
// $_SESSION['user'] = array_merge($_SESSION['user'], $user);


// ✅ Hantera POST-förfrågningar (uppdatering, lösenordsbekräftelse, kontoborttagning)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['account-action'])) {

    $post_allowedValues = ['account-update', 'pw-confirm-old', 'pw-update', 'account-destroy'];

    if (in_array($_POST['account-action'], $post_allowedValues, true)) {

        switch ($_POST['account-action']) {
            case 'account-update':
                $controller->updateAccount($id);
                exit();
            case 'pw-confirm-old':
                $controller->confirmOldPassword($id);
                exit();
            case 'pw-update':
                $controller->updatePassword($id);
                exit();
            case 'account-destroy':
                $controller->deleteAccount($id);
                exit();
        }
    }
}

header("Location: ./accountDetails.php");
exit;
