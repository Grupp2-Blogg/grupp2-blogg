<?php
require 'includes/dboconn.inc.php';
require 'includes/session_config.php';

if(!isset($_SESSION['user_id'])){
    /*die("Error: You must be logged in on your profile to post.");*/
    $_SESSION['user_id'] = 1; /*Tillfällig "användare"*/
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $title = $_POST['blogtitle'];
    $content = $_POST['blogcontent'];
    $user_id = $_SESSION['user_id'];

    $imagePath = NULL;
    if (isset($_FILES['post_image'])&& $_FILES['post_image']['error'] === UPLOAD_ERR_OK){
        $uploadDir = "uploads/";
        $fileName = basename($_FILES['post_image']['name']);
        $targetFilePath = $uploadDir . time() . "_" . $fileName;

        if (move_uploaded_file($_FILES['post_image']['tmp_name'], $targetFilePath)){
            $imagePath = $targetFilePath;
        }
        else{
            echo "Error: Couldn't upload the image!";
        }
    }


    try{
        $stmt = $pdo->prepare("INSERT INTO blogposts (blogtitle, blogcontent, user_id) VALUES (?,?,?)");
        $stmt->execute([$title, $content, $user_id]);

        echo "Your post has been uploaded!";
    }
    catch (PDOException $e){
        echo "Error: " . $e->getMessage();
    }
}
?>



<h2>Add a new post to your blog</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="blogtitle" placeholder="Post Title" required>
    <textarea name="blogcontent" placeholder="Write here..." required></textarea>
    <input type = "file" name="post_image" accept="image/*">
    <button type="submit">Publish Post</button>
</form>