<?php
require_once '../config/session_config.php';
require_once '../config/dboconn.php';
// require_once '../app/config/dboconn.php';
// require_once '../app/config/session_config.php';

if ((isset($_GET['logout']) && $_GET['logout'] === 'true')) {
    session_unset();
    session_destroy();
}

function getPostsAndUsersBySearch(PDO $pdo)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {

        if (!empty($_POST['search'])) {


            $searchTerm = "%" . $_POST['search'] . "%";

            $query = "SELECT DISTINCT
                users.id AS entity_id, 
                users.username AS name, 
                'user' AS type
            FROM users
            WHERE users.username LIKE :search
            UNION ALL
            SELECT 
                blogposts.id AS entity_id, 
                blogposts.blogtitle AS name, 
                'blogpost' AS type
            FROM blogposts
            WHERE blogposts.blogtitle LIKE :search;";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}

if (isset($_SESSION['user'])) {
    $user_id = array($_SESSION['user']['id']);
    $user_search = $pdo->prepare('SELECT image_path from users WHERE id = ?');
    $user_search->execute($user_id);
    $user = $user_search->fetch();
}

//Hämtar antalet kommentarer och likes per inlägg

$commentsCount = [];
$commentsQuery = $pdo->prepare("SELECT post_id, COUNT(*) as count FROM comments GROUP BY post_id");
$commentsQuery->execute();
$commentsResults = $commentsQuery->fetchAll(PDO::FETCH_ASSOC);

foreach ($commentsResults as $row) {
    $commentsCount[$row['post_id']] = $row['count'];
}

$likesCount = [];
$likesQuery = $pdo->prepare("SELECT post_id, COUNT(*) as count FROM likes GROUP BY post_id");
$likesQuery->execute();
$likesResults = $likesQuery->fetchAll(PDO::FETCH_ASSOC);

foreach ($likesResults as $row) {
    $likesCount[$row['post_id']] = $row['count'];
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GÄDDHÄNG</title>
    <link rel="stylesheet" href="../Styles.css">
    <script src="../darktheme.js"></script>
</head>


<body>

    <header class="top-header">
        <div class="theme-mode">
        <label>
            <input type="radio" name="scheme" value="light" id="light-mode">
            Light Mode
        </label>
        <label>
            <input type="radio" name="scheme" value="dark" id="dark-mode">
            Dark Mode
        </label>
        </div>

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
                    <a href="../profiles/profile.php" class="login-btn">Mitt Konto</a>     <!--Ändrat här //anders-->
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
            <li><a href="addpost.php">Inlägg</a></li>


        </ul>
        <div class="search-header">
            <div class="search-bar-container">
                <form action="" method="post">
                    <input type="search" name="search" id="">
                    <input type="submit" value="Sök" class="srch-btn">
                </form>
                <?php $results = getPostsAndUsersBySearch($pdo); ?>
                <?php if ($results): ?>
                    <div class="search-results"> <!-- Lägg alla resultat i en wrapper -->
                        <?php foreach ($results as $result): ?>
                            <div class="search-result-cell">
                                <?php if ($result['type'] === 'user'): ?>
                                    <a href="../profiles/profile.php?id=<?= $result['entity_id'] ?>">
                                        <img src="../icons/profile-user.png" alt="" class="search-result-icon">
                                        <?= htmlspecialchars($result['name']) ?>
                                    </a>
                                <?php elseif ($result['type'] === 'blogpost'): ?>
                                    <a href="./posts.php?id=<?= $result['entity_id'] ?>">
                                        <img src="../icons/communication.png" alt="" class="search-result-icon">
                                        <?= htmlspecialchars($result['name']) ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>



    <?php
    $stmt = $pdo->prepare('
        SELECT * FROM (
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
        WHERE blogpost_id IS NOT NULL
        ORDER BY COALESCE(post_created_at, user_created_at) DESC;
        ');

    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php foreach ($posts as $post): ?>
        <div class="div--inlägg-container">
            <a href="posts.php?id=<?= htmlspecialchars($post['blogpost_id']) ?>">
                <div>
                    <img src="/<?= htmlspecialchars($post['image_path']) ?? '' ?>" alt="<?= htmlspecialchars($post['blogtitle']) ?>">

                    <div class="div--inlägg-container--headers">

                        <h2><?= htmlspecialchars($post['blogtitle']) ?></h2>

                        <div>
                            <span>By <?= htmlspecialchars($post['username']) ?></span>
                            <span>| Created <?= date('F j, Y', strtotime($post['post_created_at'])) ?></span>
                        </div>

                        <?php
                        $content = $post['blogcontent'];
                        $words = explode(' ', strip_tags($content));
                        $excerpt = implode(' ', array_slice($words, 0, 20));
                        if (count($words) > 20) {
                            $excerpt .= ' - Klicka för att läsa mer...';
                        }
                        ?>



                        <p><?= htmlspecialchars($excerpt) ?></p>

                    <p class="comments-count"> <?= $commentsCount[$post['blogpost_id']] ?? 0 ?> kommentarer</p>
                    <p class="likes-count">🐟 <?= $likesCount[$post['blogpost_id']] ?? 0 ?> gillningar</p>

                </div>      
            </div>
        </a>


            <!--Regigera och ta bort knaparna för inläggen-->
            <?php if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $post['user_id']): ?>
                <div class="post-buttons">
                    <a href="editpost.php?id=<?= $post['blogpost_id'] ?>" class="edit-button">Redigera</a>
                    <a href="deletepost.php?id=<?= $post['blogpost_id'] ?>" class="delete-button">Ta Bort</a>
                </div>
            <?php endif; ?>
        </div>

    <?php endforeach ?>

</body>

</html>