<?php
require'../app/config/dboconn.php';
require'../app/config/session_config.php';

if(!isset($_SESSION['user']['id'])){
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
    $stmt = $pdo->prepare("DELETE FROM blogposts WHERE id = ? AND user_id =?");
    $stmt->execute([$post_id, $user_id]);

    echo "Inlägget har tagits bort!";
    echo '<meta http-equiv="refresh" content="2;url=./index.php">';
    exit();
}
?>


<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ta bort inlägg</title>
    <link rel="stylesheet" href="../Styles.css">
</head>
<body>
<div class="addpost-container">
    <h2>Är du säker på att du vill ta bort inlägget?</h2>
    <p><strong><?= htmlspecialchars($post['blogtitle']) ?></strong></p>
    <form method="POST">
        <button type="submit">Ja</button>
    </form>
    <a href="index.php">Avbryt</a>
</div>
</body>
</html>