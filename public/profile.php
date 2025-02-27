<?php 
    require_once '../app/config/session_config.php';
    require_once '../app/views/profile_view.php';

    $enterEdit = false;

    if (isset($_SESSION['enter-edit'])) {
        $enterEdit = true;
        unset($_SESSION['enter-edit']);
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
            <h2><?= "Användarinfo för " . $_SESSION['user']['username']?></h2>
            <?php 
                $user = $_SESSION['user'];

                if ($enterEdit) {
                    edit_display($user);
                }
                else {
                    normal_display($user);
                    echo '<form action="../includes/profile.inc.php" method="get">
                        <input type="submit" name="account-enter-edit" value="Redigera">
                    </form>';
                    
                }
            ?>
        </section>
    </main>
    
</body>
</html>