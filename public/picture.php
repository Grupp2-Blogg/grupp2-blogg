<?php

    require_once '../config/session_config.php';
    require_once '../config/dboconn.php';

    if ((isset($_GET['logout']) && $_GET['logout'] === 'true')) {
        session_unset();
        session_destroy();
    }

    if (isset($_SESSION['user'])) {
        $user_id = array($_SESSION['user']['id']);

        $user_search = $pdo->prepare('SELECT image_path from users WHERE id = ?');
        $user_search->execute($user_id);
        $user = $user_search->fetch();
    }


    $get_pic = $pdo->prepare('SELECT image_path, id from blogposts WHERE image_path is not NULL');
    $get_pic->execute();
    $pictures = $get_pic->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GÄDDHÄNG</title>
    <link rel="stylesheet" href="../Styles.css">
</head>


<body>

<header class="top-header">
        <img src="../fiskebi/8880968.jpg">


        <div class="login-banner">
            <div class="login-container">

                <?php if (isset($_SESSION['user'])): ?>
                    <?php if (isset($_SESSION['recent_login']) && $_SESSION['recent_login'] === 'true'): ?>
                        <p>Welcome <?= htmlspecialchars($_SESSION['user']['username']); ?>!</p>
                        <?php unset($_SESSION['recent_login']); ?>
                    <?php else: ?>
                        <p><?= htmlspecialchars($_SESSION['user']['username']); ?></p>
                    <?php endif; ?>
                    <form action="./logout.php" method="post">
                        <div class="form-button-container">
                            <button type="submit" class="login-btn">Logga ut</button>
                        </div>
                    </form>
                    <div class="form-button-container">
                        <a href="./account_details_router.php" class="login-btn">Acc Settings</a>
                    </div>
                <?php else: ?>
                    <div class="form-button-container">
                        <a href="./login.php" class="login-btn">Logga in</a>
                    </div>
                    <div class="form-button-container">
                        <a href="./signup.php" class="login-btn">Registrera</a>
                    </div>
                <?php endif; ?>

            </div>

            <?php if (isset($_SESSION['user'])): ?>
                <div class="account-picture">
                    <img src="<?= htmlspecialchars($user['image_path'] ?? '') ?>">
                </div>

            <?php endif ?>
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
        <!-- <a href="posts.php?id=<?= htmlspecialchars($pic['id']) ?>"> -->
        <div class="testpic">
            <div class="picture--div">
                <img src="/<?= htmlspecialchars($pic['image_path']) ?? ''?>">
            </div>
        </div>
        <!-- </a> -->
    <?php endforeach?>
    
</body>
</html>