<?php
    require_once '../app/config/session_config.php';

    // require_once './app/config/session_config.php';
    require_once '../app/views/login_view.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Document</title>
</head>
<body>
    <main class="page-wrapper">
        <section class="account-container">
            <?php
                check_new_user();
                check_login_fail(); 
            ?>
            <h2>Login</h2>
            <form action="../includes/login.inc.php" method="post" class="account-form">
            <div class="form-bigtext-container">
                    Username:
                    <input type="text" name="username" placeholder="Username">
                </div>
                <div class="form-bigtext-container">
                    Password:
                    <input type="password" name="pwd" id="" placeholder="Password">
                </div>
                <div class="form-button-container">
                    <input type="submit" name="submit" value="Login" class="form-button">
                </div>
            </form>
        </section>
    </main>
</body>
</html>