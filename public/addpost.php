<?php
require '../app/config/dboconn.php';
require '../app/config/session_config.php';

if(!isset($_SESSION['user']['id'])){
    die("Error: Du måste vara inloggad för att skapa inlägg.");
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $title = $_POST['blogtitle'];
    $content = $_POST['blogcontent'];
    $user_id = $_SESSION['user']['id'];

    $imagePath = NULL;
    if (isset($_FILES['post_image'])&& $_FILES['post_image']['error'] === UPLOAD_ERR_OK){
        $uploadDir = "uploads/";
        $fileName = basename($_FILES['post_image']['name']);
        $targetFilePath = $uploadDir . time() . "_" . $fileName;

        if (move_uploaded_file($_FILES['post_image']['tmp_name'], $targetFilePath)){
            $imagePath = $targetFilePath;
        }
        else{
            echo "Error: Kunde inte ladda upp bilden!";
        }
    }


    try{
        $stmt = $pdo->prepare("INSERT INTO blogposts (blogtitle, blogcontent, user_id) VALUES (?,?,?)");
        $stmt->execute([$title, $content, $user_id]);

        echo "Ditt inlägg har blivit uppladdat!";
    }
    catch (PDOException $e){
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skapa ett nytt inlägg</title>
    <link rel="stylesheet" href="Styles.css">
</head>
<body>
<div class="addpost-container">
    <h2>Gör ett nytt inlägg</h2>
    <form method="POST" enctype="multipart/form-data" class="addpost-form">
        <input type="text" name="blogtitle" placeholder="Titel" required>
        <textarea name="blogcontent" placeholder="Skriv här..." required></textarea>
        <input type="file" name="post_image" accept="image/*">
        <button type="submit">Publicera</button>
    </form>
</div>
