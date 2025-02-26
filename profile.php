<?php 
    require_once './includes/session_config.php';
    require_once './includes/login_view.inc.php';

    if (isset($_SESSION['user'])) {

        $user = $_SESSION['user'];

        unset($_SESSION['user']);

    }
    else {
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
    <h2><?= "Welcome " . $user['username'] . "!<br>" ?></h2>
    
</body>
</html>