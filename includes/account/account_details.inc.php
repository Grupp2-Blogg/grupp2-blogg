<?php


if (!isset($_SESSION['user'])) {
    header("Location: ./login.php");
    exit;
}

try {

    require_once '../app/config/dboconn.php';
    require_once '../app/models/account_details_model.php';
    require_once '../app/controllers/account_details_contr.php';


    // Om användaren tryckt någon av "EDIT"-knapparna, sätt session variabel.
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['account-action'])) {

        $get_allowedValues = ['account-enter-edit', 'pw-enter-confirm-old'];

        if (in_array($_GET['account-action'], $get_allowedValues, true)) {

            $_SESSION['enter-edit'] = $_GET['account-action'];

            header("Location: ./account_details.php");
            exit;
        }
    }


    $id = $_SESSION['user']['id'];
    $user = get_all_userinfo_byID($pdo, $id);
    $errors = [];

    if (!$user) {

        $errors["invalid_fetch"] = "Kunde inte hämta användare";
        $_SESSION['errors_account'] = $errors;

        header("Location: ./login.php");
        exit;
    } else {

        unset($_SESSION['user']);

        $_SESSION['user'] = $user;
    }


    // Om användaren tryckt "Spara ändringar" knappen. Kör insert till databas(efter massa validering)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['account-action'])) {

        $post_allowedValues = ['account-update', 'pw-confirm-old', 'pw-update'];

        if (in_array($_POST['account-action'], $post_allowedValues, true)) {

            if ($_POST['account-action'] === 'pw-confirm-old') {
                handle_pwd_confirm($pdo, $id);
            }
            if ($_POST['account-action'] === 'pw-update') {
                handle_pwd_update($pdo, $id);
            }
            if ($_POST['account-action'] === 'account-update') {
                handle_account_update($pdo, $id);
            }
        }
    }


    header("Location: ./account_details.php");
    $stmt = null;
    $pdo = null;
    exit;
} catch (PDOException $e) {
    $stmt = null;
    $pdo = null;
    die("Query failed: " .  $e->getMessage());

    // error_log($e->getMessage(), 3, 'C:/xampp/htdocs/myCode/grupp2-blogg/error.log');
    // header("Location: ../public/error.php");
    exit;
}
