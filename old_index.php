<?php

require_once './session_config.php';

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
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if (isset($_SESSION['recent_login']) && $_SESSION['recent_login'] === 'true'): ?>
                        <p>Welcome <?= htmlspecialchars($_SESSION['user']['username']); ?>!</p>
                        <?php unset($_SESSION['recent_login']); ?>
                    <?php else: ?>
                        <p><?= htmlspecialchars($_SESSION['user']['username']); ?></p>
                    <?php endif; ?>
                    <form action="logout.php" method="post">
                        <div class="form-button-container">
                            <button type="submit" class="form-button">Logga ut</button>
                        </div>
                    </form>
                    <div class="form-button-container">
                        <a href="./account_details_router.php" class="form-button">Acc Settings</a>
                    </div>
                <?php else: ?>
                    <div class="form-button-container">
                        <a href="login.php" class="form-button">Logga in</a>
                    </div>
                    <div class="form-button-container">
                        <a href="signup.php" class="form-button">Registrera</a>
                    </div>
                <?php endif; ?>
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