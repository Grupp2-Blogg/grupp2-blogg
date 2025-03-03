<form action="./account_redirect.php" method="post" class="account-form">
    <div class="form-bigtext-container">
        *Användarnamn:
        <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($_SESSION['user']['username']) ?>">
    </div>
    <div class="form-smalltext-container">
        Förnamn:
        <input type="text" name="firstname" placeholder="First name" value="<?= htmlspecialchars($_SESSION['user']['firstname'] ?? '') ?>">
    </div>
    <div class="form-smalltext-container">
        Efternamn:
        <input type="text" name="lastname" placeholder="Last name" value="<?= htmlspecialchars($_SESSION['user']['lastname'] ?? '') ?>">
    </div>
    <div class="form-bigtext-container">
        *Email:
        <input type="text" name="email" placeholder="Email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>">
    </div>
    <div class="form-gender-container">
        Kön:
        <input type="radio" name="gender" id="male" value="male" <?= ($_SESSION['user']['gender'] === 'male' ? 'checked' : '') ?>>
        <label for="male">Man</label>
        <input type="radio" name="gender" id="female" value="female" <?= ($_SESSION['user']['gender'] === 'female' ? 'checked' : '') ?>>
        <label for="female">Kvinna</label>
        <input type="radio" name="gender" id="non-binary" value="non-binary" <?= ($_SESSION['user']['gender'] === 'non-binary' ? 'checked' : '') ?>>
        <label for="non-binary">Icke-binär</label>
        <input type="radio" name="gender" id="no-answer" value="no-answer" <?= (empty($_SESSION['user']['gender']) || $_SESSION['user']['gender'] === 'no-answer') ? 'checked' : '' ?>>
        <label for="no-answer">Vill ej svara</label>
    </div>
    <div class="form-birthyear-container">
        Födelseår:
        <select name="birthyear" id="">
            <option value="" <?= (empty($_SESSION['user']['birthyear']) ? 'selected' : '') ?>>----</option>
            <?php
            $currentYear = date("Y");
            $earliestYear = $currentYear - 110;
            for ($i = $currentYear; $i >= $earliestYear; $i--) {
                $selected = ($_SESSION['user']['birthyear'] == $i ? 'selected' : '');
                echo "<option value='{$i}' {$selected}>{$i}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-button-container">
        <button type="submit" class="form-button" name="account-action" value="account-update">Spara ändringar</button>
    </div>
    <p class="required-fields">* Obligatoriska fält</p>
</form>