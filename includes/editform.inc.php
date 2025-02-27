<form action="" method="post" style="display:flex; flex-direction:column;">

    <input type="text" name="username" id="" value="<?= $user['username']?>">
    <input type="text" name="email" id="" value="<?= $user['email']?>">
    <input type="text" name="pwd" id="" placeholder="Enter new password">
    <input type="text" name="firstname" id="" value="<?= $user['firstname']?>">
    <input type="text" name="lastname" id="" value="<?= $user['lastname']?>">
    <input type="text" name="gender" id="" value="<?= $user['gender']?>">
    <input type="text" name="birthyear" id="" value="<?= $user['birthyear']?>">
    <input type="text" name="created_at" id="" value="<?= $user['created_at']?>">

    <input type="submit" value="Save Changes">

</form>