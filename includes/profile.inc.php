<?php
require_once '../app/config/session_config.php';

if (isset($_SESSION['user'])) {

    $id = $_SESSION['user']['id'];
    
    try {
        
        require_once '../app/config/dboconn.php';
        require_once '../app/models/profile_model.php';
        require_once '../app/controllers/profile_contr.php';
        
        
        
        
        $errors = [];
        
        $user = get_all_userinfo_byID($pdo, $id);
    
        if (empty($user) || !isset($user) || $user === NULL) {

            $errors["invalid_fetch"] = "Couldn't retrieve user data";
            $_SESSION['errors_login'] = $errors;

            header("Location: ../public/login.php");
            exit;
        } else {

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                if (isset($_GET['account-enter-edit'])) {
    
                    $_SESSION['enter-edit'] = 'true';
    
                }
                
    
    
            }

            $_SESSION['user'] = $user;
            header("Location: ../public/profile.php");
            die();
        }

    
    
    } catch (PDOException $e) {
        die("Query failed: " .  $e->getMessage());
    }
}

