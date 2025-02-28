<?php
require_once '../app/config/session_config.php';
require_once '../app/views/profile_details_view.php';

if (!isset($_SESSION['user'])) {

    header("Location: ./login.php");
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
            <h2>Personuppgifter</h2>
            <?php
            check_edit_mode();
            check_profile_update_errors();
            ?>
        </section>
    </main>

</body>

</html>