<?php
// require_once '../app/config/dboconn.php';
require_once '../app/config/session_config.php';



if ((isset($_GET['logout']) && $_GET['logout'] === 'true')) {
    session_unset();
    session_destroy();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GÄDDHÄNG</title>
    <link rel="stylesheet" href="Styles.css">
</head>


<body>

    <header class="top-header">
        <img src="./fiskebi/8880968.jpg">


        <div class="login-banner">
            <div class="login-container">
                <?php
                if (isset($_SESSION['account-deleted']) && $_SESSION['account-deleted'] === 'true') {
                    echo "<h3>Account deleted!</h3>";
                    unset($_SESSION['account-deleted']);
                    session_unset();
                    session_destroy();
                }
                if (isset($_SESSION['user'])) {

                    if (isset($_SESSION['recent_login']) && $_SESSION['recent_login'] === 'true') {
                        echo "<p>Welcome " . htmlspecialchars($_SESSION['user']['username']) . "!</p>";
                        unset($_SESSION['recent_login']);
                    } else {

                        echo "<p>" . htmlspecialchars($_SESSION['user']['username']) . "</p>";
                    }

                    // echo '<a href="./account_redirect.php" class="login-btn">Acc settings</a>';
                    // echo '<a href="./index.php?logout=true" class="login-btn">Logga ut</a>';
                    require_once '../includes/account/index_acc_buttons_after.inc.php';
                } else {
                    require_once '../includes/account/index_acc_buttons_before.inc.php';
                }
                ?>
                <!-- <a href="./login.php" class="login-btn">Logga in</a>
                <a href="./signup.php" class="register-btn">Registrera</a> -->

            </div>
            <div class="account-picture">
                <img src="./fiskebi/dominik.jpg" alt="">
            </div>
        </div>


    </header>


    <nav class="navbar">
        <ul>
            <li><a href="#">Hem</a></li>
            <li><a href="#">Bilder</a></li>
            <li><a href="#">Recept</a></li>
            <li><a href="addpost.php">Inlägg</a></li>

        </ul>
    </nav>

    <div class="content">
        <h2>GÄDDHÄNG!</h2>
        <p>kungligaste bloggen</p>
        <p>Napp och gäng, gäddhäng</p>
        <p style="height: 1500px;"></p>
    </div>







</body>

</html>