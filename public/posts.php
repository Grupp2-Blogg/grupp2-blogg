<?php
require_once '../app/config/dboconn.php';
require_once '../app/config/session_config.php';

if ((isset($_GET['logout']) && $_GET['logout'] === 'true')) {
    session_unset();
    session_destroy();
}

$post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$post_id){
    header("Location: index.php");
    exit;
}

$get = $pdo->prepare("SELECT * FROM (
            SELECT B.id AS blogpost_id, B.blogtitle, B.blogcontent, B.image_path, B.created_at AS post_created_at,
                   U.id AS user_id, U.username, U.created_at AS user_created_at
            FROM blogposts AS B 
            LEFT JOIN users AS U ON B.user_id = U.id
            UNION
            SELECT B.id AS blogpost_id, B.blogtitle, B.blogcontent, B.image_path, B.created_at AS post_created_at,
                   U.id AS user_id, U.username, U.created_at AS user_created_at
            FROM blogposts AS B 
            RIGHT JOIN users AS U ON B.user_id = U.id
        ) AS combined_results
        WHERE blogpost_id = ?
        ORDER BY COALESCE(post_created_at, user_created_at);");
$get->execute([$post_id]);
$post = $get->fetch();

if(!$post){
    header("Location: index.php");
    exit;
}

// Lägg in för kommentarer och likes sedan.

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post["blogtitle"]) ?></title>
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
            <li><a href="index.php">Hem</a></li>
            <li><a href="#">Bilder</a></li>
            <li><a href="#">Recept</a></li>
            <li><a href="addpost.php">Inlägg</a></li>

        </ul>
    </nav>


        <div >
            <img src="<?= htmlspecialchars($post['image_path'] ?? '') ?>" alt="<?= htmlspecialchars($post['blogtitle'])?>">
            <div>
                <h2><?= htmlspecialchars($post['blogtitle']) ?></h2>
                
                <div>
                    <span>By <?= htmlspecialchars($post['username'])?></span>
                    <span>| Created <?= date('F j, Y', strtotime($post['post_created_at'])) ?></span>
                </div>

                <p><?= htmlspecialchars($post['blogcontent']) ?></p>
            </div>
        </div>
    
</body>
</html>