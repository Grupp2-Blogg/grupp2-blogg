<?php

    require_once '../app/config/dboconn.php';
    require_once '../app/config/session_config.php';

    $get_pic = $pdo->prepare('SELECT image_path from blogposts WHERE image_path is not NULL');
    $get_pic->execute();
    $pictures = $get_pic->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilder</title>
</head>
<body>

    <?php foreach ($pictures as $pic): ?>
        <img src="/<?= htmlspecialchars($pic['image_path']) ?? ''?>">
    <?php endforeach?>
    
</body>
</html>