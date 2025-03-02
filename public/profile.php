<<<<<<<< HEAD:public/account_details.php
<?php
require_once '../app/config/session_config.php';
require_once '../app/views/account_details_view.php';
========
<?php 
    require_once '../app/config/session_config.php';
    require_once '../app/views/login_view.php';
>>>>>>>> main:public/profile.php

    if (!isset($_SESSION['user'])) {

        header("Location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Blogg Profil</title>
</head>
<body>
    <main class="page-wrapper">
        <section class="account-container">
<<<<<<<< HEAD:public/account_details.php
            <h2>Personuppgifter</h2>
            <?php
            check_edit_mode();
            check_account_update_errors();

            if (isset($_SESSION['pwd_update_complete'])) {
                echo $_SESSION['pwd_update_complete'];
                unset($_SESSION['pwd_update_complete']);
            }

            ?>
========
            <h2><?= "Welcome " . $_SESSION['user']['username'] . "!" ?></h2>
            <p><?= "User_ID: " . $_SESSION['user']['id']?></p>
>>>>>>>> main:public/profile.php
        </section>
    </main>
    
</body>
</html>