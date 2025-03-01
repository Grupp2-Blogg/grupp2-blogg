<?php
require_once '../app/config/dboconn.php'; 
    require_once '../app/config/session_config.php';
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
                if ((isset($_GET['logout']) && $_GET['logout'] == 'true')) {
                    unset($_SESSION['user']);
                }

                if (isset($_SESSION['user']['id'])) {

                    echo "<p>Welcome " . $_SESSION['user']['username'] . "!</p>";

                    echo '<a href="./index.php?logout=true" class="login-btn">Logga ut</a>';
                } else {
                    echo '<a href="./login.php" class="login-btn">Logga in</a>
                          <a href="./signup.php" class="register-btn">Registrera</a>';
                }
                ?>
                <!-- <a href="./login.php" class="login-btn">Logga in</a>
                <a href="./signup.php" class="register-btn">Registrera</a> -->

            </div>
            <div class="profile-picture">
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

  <!--farzad visa alla inlägg-->

  <div >
    <h1>Blogginlägg</h1>
    <?php
    try {
        // Hämta blogginlägg från tabellen blogposts i fallande ordning (nyaste först)
        $stmt = $pdo->prepare("SELECT * FROM blogposts ORDER BY id DESC");
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($posts) {
    
            foreach ($posts as $post) {
                echo '<div >';
            
                echo '<div >' . htmlspecialchars($post['blogtitle']) . '</div>';
            
                echo '<div >' . nl2br(htmlspecialchars($post['blogcontent'])) . '</div>';
                
                if (!empty($post['post_image'])) {
                    echo '<div class="post-image"><img src="' . htmlspecialchars($post['post_image']) . '" alt="Bloggbild"></div>';
                }
                echo '</div>';
            }
        } else {
            echo '<p>Inga inlägg hittades.</p>';
        }
    } catch (PDOException $e) {
        
        echo "Fel vid hämtning av inlägg: " . $e->getMessage();
    }
    ?>
</div>






</body>

</html>