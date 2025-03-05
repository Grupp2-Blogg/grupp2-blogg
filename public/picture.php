<?php

    require_once '../app/config/dboconn.php';
    require_once '../app/config/session_config.php';

    if ((isset($_GET['logout']) && $_GET['logout'] === 'true')) {
        session_unset();
        session_destroy();
    }

    $get_pic = $pdo->prepare('SELECT image_path from blogposts WHERE image_path is not NULL');
    $get_pic->execute();
    $pictures = $get_pic->fetchAll(PDO::FETCH_ASSOC);

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
                if (isset($_SESSION['user'])) {

                    if (isset($_SESSION['recent_login']) && $_SESSION['recent_login'] === 'true') {
                        echo "<p>Welcome " . htmlspecialchars($_SESSION['user']['username']) . "!</p>";
                        unset($_SESSION['recent_login']);
                    } else {

                        echo "<p>" . htmlspecialchars($_SESSION['user']['username']) . "</p>";
                    }

                    echo '<a href="./account_redirect.php" class="login-btn">Acc settings</a>';
                    echo '<a href="./index.php?logout=true" class="login-btn">Logga ut</a>';
                } else {
                    echo '<a href="./login.php" class="login-btn">Logga in</a>
                          <a href="./signup.php" class="register-btn">Registrera</a>';
                }
                ?>

            </div>

            <?php if (isset($_SESSION['user'])): ?>
                <div class="account-picture">
                    <img src="<?= htmlspecialchars($user['image_path'] ?? '') ?>">
                </div>
                
            <?php endif?>
        </div>


    </header>


    <nav class="navbar">
        <ul>
            <li><a href="index.php">Hem</a></li>
            <li><a href="picture.php">Bilder</a></li>
            <li><a href="#">Recept</a></li>
            <li><a href="addpost.php">Inlägg</a></li>

        </ul>
    </nav>

    <?php foreach ($pictures as $pic): ?>
        <img src="/<?= htmlspecialchars($pic['image_path']) ?? ''?>">
    <?php endforeach?>
    
</body>
</html>