<?php
session_start();

require_once '../config/dboconn.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user']['id']; 


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$user) {
    echo "Fel: Kunde inte hämta användarinformation.";
    exit();
}

$queryPosts = "SELECT id, blogtitle AS title, blogcontent AS content, image_path, created_at FROM blogposts WHERE user_id = :user_id ORDER BY created_at DESC";
$stmtPosts = $pdo->prepare($queryPosts);
$stmtPosts->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtPosts->execute();
$posts = $stmtPosts->fetchAll(PDO::FETCH_ASSOC);


$posts = $posts ?? []; 



?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitt Konto</title>
    <link rel="stylesheet" href="../profiles/profiles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<header class="top-header">
        <img src="./fiskebi/Logga.png">


        <div class="login-banner">
            <div class="login-container">

                <!-- // if (isset($_SESSION['user'])) {

                //     if (isset($_SESSION['recent_login']) && $_SESSION['recent_login'] === 'true') {
                //         echo "<p>Welcome " . htmlspecialchars($_SESSION['user']['username']) . "!</p>";
                //         unset($_SESSION['recent_login']);
                //     } else {

                //         echo "<p>" . htmlspecialchars($_SESSION['user']['username']) . "</p>";
                //     }

                //     echo '<a href="./account_redirect.php" class="login-btn">Acc settings</a>';
                //     echo '<a href="./index.php?logout=true" class="login-btn">Logga ut</a>';
                // } else {
                //     echo '<a href="./login.php" class="login-btn">Logga in</a>
                //           <a href="./signup.php" class="register-btn">Registrera</a>';
                // }
                //  -->
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if (isset($_SESSION['recent_login']) && $_SESSION['recent_login'] === 'true'): ?>
                        <p>Welcome <?= htmlspecialchars($_SESSION['user']['username']); ?>!</p>
                        <?php unset($_SESSION['recent_login']); ?>
                    <?php else: ?>
                        <p><?= htmlspecialchars($_SESSION['user']['username']); ?></p>
                    <?php endif; ?>
                    <form action="../public/logout.php" method="post">
                        <div class="form-button-container">
                            <a href="../public/logout.php" class="login-btn">Logga ut</a>
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
            <li><a href="../public/index.php">Hem</a></li>
            <li><a href="../public/picture.php">Bilder</a></li>
            <li><a href="#">Recept</a></li>
            <li><a href="../public/addpost.php">Inlägg</a></li>

        </ul>
    </nav>









    <div class="profile-container">

        <!-- sidebar brotha -->
        <div class="profile-sidebar">

        

           

            <div class="profile-picture-container">
                <img src="../uploads/<?= htmlspecialchars($user['profile_picture'] ?? 'default.jpg') ?>" alt="Profilbild" class="profile-pic">
                <a href="edit_profile.php" class="edit-btn">Ändra Profilbild</a>
            </div>


            <h2><?= htmlspecialchars($user['username']) ?></h2>

        
            <div class="settings-icon">
                <a href="../public/account_details_router.php">
                <i class="fa-solid fa-gear"></i>
                </a>
             </div> 
            
            
            
           

            <div class="about-section">
                <h3>Om mig</h3>
                <p><?= htmlspecialchars($user['about_me'] ?? 'beskriv beskriv.') ?></p>
                <a href="edit_about.php" class="edit-btn">Redigera Om mig</a>
            </div>
        </div>

        <!-- välkommen och inlägg -->
        <div class="profile-content">
            <div class="welcome-box">
                <h1>Välkommen till min profil, <?= htmlspecialchars($user['username']) ?>!</h1>
                <p>Här kan du se alla mina inlägg. </p>
            </div>
            
            <h2>Mina Inlägg</h2>
          
            <?php if (count($posts) > 0): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <a href="../public/posts.php?id=<?= htmlspecialchars($post['id']) ?>">
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        </a>
                        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        <?php if (!empty($post['image'])): ?>
                            <img src="../uploads/<?= htmlspecialchars($post['image']) ?>" alt="Inläggsbild" class="post-image">
                        <?php endif; ?>
                        <small>Publicerad: <?= date('Y-m-d H:i', strtotime($post['created_at'])) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Du har inga inlägg ännu.</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
