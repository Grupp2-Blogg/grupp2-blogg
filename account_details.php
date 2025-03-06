<?php

declare(strict_types=1);
require_once './session_config.php';
require_once './dboconn.php';
require_once './AccountDetailsController.php';

if (!isset($_SESSION['user'])) {

    header("Location: ./login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Blogg Profil</title>
</head>

<body>
    <main class="page-wrapper">
        <section class="account-container">
            <h2>Personuppgifter</h2>
            <?php
            $controller = new AccountDetailsController($pdo);
            $controller->checkEditMode();
            $controller->checkAccountUpdateErrors();

            if (isset($_SESSION['pwd_update_complete'])) {
                echo $_SESSION['pwd_update_complete'];
                unset($_SESSION['pwd_update_complete']);
            }
            ?>
            <div class="form-button-container">
                <a href="./logout.php?logout=true" class="form-button">Logga ut</a>
                <a href="./index.php" class="form-button">Startsida</a>
            </div>

        </section>
    </main>

</body>

</html>