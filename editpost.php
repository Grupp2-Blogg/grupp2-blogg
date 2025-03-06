<?php
require '../app/config/dboconn.php';
require '../app/config/session_config.php';

if (!isset($_SESSION['user']['id'])){
    die("Error: Du måste vara inloggad");
}

$user_id = $_SESSION['user']['id'];
$post_id = $_GET['id'] ?? null;

if (!$post_id){
    die("Error: Du har inte valt ett inlägg");
}


$stmt = $pdo->prepare("SELECT * FROM blogposts WHERE id = ? AND user_id = ?");
$stmt->execute([$post_id, $user_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$post){
    die("Error: Inlägget hittades inte eller saknar rättigheter.");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $title = $_POST['blogtitle'];
    $content = $_POST['blogcontent'];
    $stmt = $pdo->prepare("UPDATE blogposts SET blogtitle = ?, blogcontent = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $content, $post_id, $user_id]);

    echo "Inlägget har uppdaterats!";
    echo '<meta http-equiv="refresh" content="2;url=./index.php">';
    exit();
}
?>



<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redigera inlägg</title>
    <link rel="stylesheet" href="../Styles.css">
</head>
<body>
<div class="addpost-container">
    <h2>Redigera inlägg</h2>
    <form method="POST" class="addpost-form">
        <input type="text" name="blogtitle" value="<?= htmlspecialchars($post['blogtitle']) ?>" required>
        <textarea name="blogcontent" required><?= htmlspecialchars($post['blogcontent']) ?></textarea>
        <button type="submit">Uppdatera</button>
    </form>
</div>
</body>
</html>