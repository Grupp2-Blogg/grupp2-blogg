<?php

declare(strict_types=1);
require_once '../config/session_config.php';
require_once '../config/dboconn.php';
require_once '../controllers/AccountDetailsController.php';

if (!isset($_SESSION['user'])) {

    header("Location: ./login.php");
    exit;
}

$controller = new AccountDetailsController($pdo);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Blogg Profil</title>
</head>

<body>
    <main class="page-wrapper">
        <section class="account-container">
            <h2>Personuppgifter</h2>
            <?php
            $controller->checkEditMode();
            $controller->checkAccountUpdateErrors();

            if (isset($_SESSION['pwd_update_complete'])) {
                echo $_SESSION['pwd_update_complete'];
                unset($_SESSION['pwd_update_complete']);
            }
            echo '<div class="form-button-container-a">';
            echo '<a href="./index.php?logout=true" class="form-button-a">Logga ut</a>';
            echo '<a href="./index.php" class="form-button-a">Startsida</a>';
            echo '</div>';

            ?>
        </section>
    </main>

</body>

</html>