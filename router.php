<?php
require_once './session_config.php';
require_once './dboconn.php';
require_once './AccountDetailsController.php';
require_once './loginController.php';
require_once './signupController.php';


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
