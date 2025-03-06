<?php
require_once '../config/session_config.php';
require_once '../config/dboconn.php';
require_once '../controllers/AccountDetailsController.php';
require_once '../controllers/LoginController.php';
require_once '../controllers/SignupController.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action'])) {

        switch ($_POST['action']) {
            case 'register':
                $controller = new SignupController($pdo);
                $controller->registerUser();
                break;
            case 'login':
                $controller = new LoginController($pdo);
                $controller->loginUser();
                break;
        }
    }

    
} else {
    header("Location: ./accountDetails.php");
    exit;
}
