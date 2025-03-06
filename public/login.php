<?php

declare(strict_types=1);

require_once '../config/session_config.php';
require_once '../config/dboconn.php';
require_once '../controllers/LoginController.php';

$controller = new LoginController($pdo);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Document</title>
</head>

<body>
    <main class="page-wrapper">
        <section class="account-container">
            <?php
            $controller->checkForNewUser();
            $controller->checkForLoginErrors();
            ?>
            <h2>Login</h2>
            <form action="./router.php" method="post" class="account-form">
                <div class="form-bigtext-container">
                    Username:
                    <input type="text" name="username" placeholder="Username">
                </div>
                <div class="form-bigtext-container">
                    Password:
                    <input type="password" name="pwd" id="" placeholder="Password">
                </div>
                <div class="form-button-container">
                    <input type="submit" name="action" value="login" class="form-button">
                </div>
            </form>
        </section>
    </main>
</body>

</html>