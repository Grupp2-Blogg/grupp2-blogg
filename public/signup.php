<?php
    require_once '../app/config/session_config.php';
    require_once '../app/views/signup_view.php';
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
            <h2>Registrering</h2>

            <form action="../includes/signup.inc.php" method="post" class="account-form">
                <div class="form-bigtext-container">
                    *Användarnamn:
                    <input type="text" name="username" placeholder="Username">
                </div>
                <div class="form-smalltext-container">
                    Förnamn:
                    <input type="text" name="firstname" id="" placeholder="First name">
                </div>
                <div class="form-smalltext-container">
                    Efternamn:
                    <input type="text" name="lastname" id="" placeholder="Last name">
                </div>
                <div class="form-bigtext-container">
                    *Lösenord:
                    <input type="password" name="pwd" id="" placeholder="Password">
                </div>
                <div class="form-bigtext-container">
                    *Bekräfta lösenord:
                    <input type="password" name="pwd-repeat" id="" placeholder="Password">
                </div>
                <div class="form-bigtext-container">
                    *Email:
                    <input type="text" name="email" id="" placeholder="Email">
                </div>
                <div class="form-gender-container">
                    Kön:
                    <input type="radio" name="gender" id="male" value="male">
                    <label for="male">Man</label>
                    <input type="radio" name="gender" id="female" value="female">
                    <label for="female">Kvinna</label>
                    <input type="radio" name="gender" id="non-binary" value="non-binary">
                    <label for="non-binary">Icke-binär</label>
                    <input type="radio" name="gender" id="no-answer" value="no-answer" checked>
                    <label for="no-answer">Vill ej svara</label>
                </div>
                <div class="form-birthyear-container">
                    Födelseår:
                    <select name="birthyear" id="">
                        <option value="" selected>----</option>
                        <?php
                            populate_year_select_options(110);
                        ?>
                    </select>
                </div>
                <div class="tc-checkbox-container">
                    <input type="checkbox" name="tc" id="">*Jag har läst och accepterar villkoren
                </div>
                <div class="form-button-container">
                    <input type="submit" name="submit" value="Register" class="form-button">
                </div>

            </form>
            <p class="required-fields">* obligatoriska fält</p>
            <?php
                check_signup_errors();
            ?>
        </section>
    </main>

</body>

</html>