<?php 
    require_once './includes/session_config.php';
    require_once './includes/login_view.inc.php';

    if (!isset($_SESSION['user'])) {

        header("Location: login.php");
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
            <h2><?= "Welcome " . $_SESSION['user']['username'] . "!" ?></h2>
            <p><?= "User_ID: " . $_SESSION['user']['id']?></p>
        </section>
    </main>
    
</body>
</html>