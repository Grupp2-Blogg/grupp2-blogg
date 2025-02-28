<?php
require_once '../app/config/session_config.php';
require_once '../app/views/signup_view.php';

if (isset($_SESSION['user'])) {
    header("Location: ./index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Bloggsajt</title>
</head>

<body>
    <main class="page-wrapper">
        <section class="account-container">
            <h2>Signup</h2>

            <form action="../includes/signup.inc.php" method="post" class="account-form">
                <div class="form-bigtext-container">
                    *Username:
                    <input type="text" name="username" placeholder="Username">
                </div>
                <div class="form-smalltext-container">
                    First name:
                    <input type="text" name="firstname" id="" placeholder="First name">
                </div>
                <div class="form-smalltext-container">
                    Last name:
                    <input type="text" name="lastname" id="" placeholder="Last name">
                </div>
                <div class="form-bigtext-container">
                    *Password:
                    <input type="password" name="pwd" id="" placeholder="Password">
                </div>
                <div class="form-bigtext-container">
                    *Email:
                    <input type="text" name="email" id="" placeholder="Email">
                </div>
                <div class="form-gender-container">
                    Gender:
                    <input type="radio" name="gender" id="male" value="male">
                    <label for="male">Male</label>
                    <input type="radio" name="gender" id="female" value="female">
                    <label for="female">Female</label>
                    <input type="radio" name="gender" id="non-binary" value="non-binary">
                    <label for="non-binary">Non-binary</label>
                    <input type="radio" name="gender" id="no-answer" value="no-answer" checked>
                    <label for="no-answer">No answer</label>
                </div>
                <div class="form-birthyear-container">
                    Year of birth:
                    <select name="birthyear" id="">
                        <option value="" selected>----</option>
                        <?php
                        populate_year_select_options(110);
                        ?>
                    </select>
                </div>
                <div class="tc-checkbox-container">
                    <input type="checkbox" name="tc" id="">*I have read and accept T&C
                </div>
                <div class="form-button-container">
                    <input type="submit" name="submit" value="Register" class="form-button">
                </div>

            </form>
            <p class="required-fields">* required fields</p>
            <?php
            check_signup_errors();
            ?>
        </section>
    </main>

</body>

</html>