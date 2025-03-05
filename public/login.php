<?php
    require_once '../app/config/session_config.php';
    require_once '../app/views/UserView.php';

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
                checkForNewUser();
                checkForLoginFail();
                
            ?>
            <h2>Login</h2>
            <form action="./account_action_router.php" method="post" class="account-form">
            <div class="form-bigtext-container">
                    Username:
                    <input type="text" name="username" placeholder="Username">
                </div>
                <div class="form-bigtext-container">
                    Password:
                    <input type="password" name="pwd" id="" placeholder="Password">
                </div>
                <div class="form-button-container">
                    <!-- <input type="submit" name="account-action" value="Login" class="form-button">
                      -->
                      <button type="submit" class="form-button" name="account-action" value="account-login">Login</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>