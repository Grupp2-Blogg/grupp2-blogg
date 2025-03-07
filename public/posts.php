<?php
require_once '../config/dboconn.php';
require_once '../config/session_config.php';
require_once '../models/Comment.php';

if ((isset($_GET['logout']) && $_GET['logout'] === 'true')) {
    session_unset();
    session_destroy();
}

$post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$post_id) {
    header("Location: ../public/index.php");
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

if (!$post) {
    header("Location: ../public/index.php");
    exit;
}

// Lägg in för kommentarer och likes sedan.

$commentDel = new Comment($pdo);
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])){
    if(!isset($_SESSION['user']['id'])){
        echo "Error: Du måste vara inloggad för att kunna kommentera";
    }
    else{
        $commentText = trim($_POST['comment']);
        $user_id = $_SESSION['user']['id'];

        if(!empty($commentText)){
            $commentDel->addComment($post_id, $user_id, $commentText);
            header("Location: posts.php?id=" . $post_id);
            exit();
        }

        else{
            echo "Kommentarsfältet får inte vara tomt";
        }
    }
}

$comments = $commentDel->commentsPostId($post_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post["blogtitle"]) ?></title>
    <link rel="stylesheet" href="../Styles.css">
</head>

<body>

    <header class="top-header">
        <img src="./fiskebi/Logga.png">


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
                            <a href="./logout.php" class="login-btn">Logga ut</a>
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
                        <a href="./signup.php" class="register-btn">Registrera</a>
                    </div>
                <?php endif; ?>

            </div>
            <div class="account-picture">
                <img src="../fiskebi/dominik.jpg" alt="">
            </div>
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


    <div class="blog-posts">
        <img src="/<?= htmlspecialchars($post['image_path']) ?? '' ?>" alt="<?= htmlspecialchars($post['blogtitle']) ?>">

        <div class="blog-posts--headers">
            <h2><?= htmlspecialchars($post['blogtitle']) ?></h2>
            <p>Av: <?= htmlspecialchars($post['username']) ?></p>
            <p>Skapad: <?= date('F j, Y', strtotime($post['post_created_at'])) ?></p>
        </div>

            <div class="blog-posts--contents">
                <p><?= htmlspecialchars($post['blogcontent']) ?></p>
            </div>
        </div>


<div class="comments-section">
    <h3>Kommentarer</h3>
    <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $comment): ?>
            <div class ="comment">
                <p><strong><?= htmlspecialchars($comment['username']) ?></strong></p>
                <p><?=nl2br(htmlspecialchars($comment['content'])) ?></p>
                <small><?= $comment['created_at'] ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Inga kommentarer än. Var först med att kommentera!</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['user']['id'])): ?>
        <form method="POST" class="comment-form">
            <textarea name="comment" placeholder="Skriv kommentaren här... " required></textarea>
            <button type="submit">Skicka</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Logga in</a> för att kommentera</p>
    <?php endif; ?>

</div>
    
</body>

</html>