<?php
require_once '../app/config/session_config.php';
require_once '../app/views/UserView.php';

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
    <link rel="stylesheet" href="./css/style.css">
    <title>Blogg Profil</title>
</head>

<body>
    <main class="page-wrapper">
        <section class="account-container">
            <h2>Personuppgifter</h2>
            <?php
            checkAccUpdateErrors();

            require_once '../includes/account/account_info.inc.php';

            if (isset($_SESSION['pwd_update_complete'])) {
                echo $_SESSION['pwd_update_complete'];
                unset($_SESSION['pwd_update_complete']);
            }
            echo '<a href="./index.php?logout=true" class="login-btn">Logga ut</a>';
            echo '<a href="./index.php" class="login-btn">Startsida</a>';
            
            ?>
        </section>
    </main>

</body>

</html>