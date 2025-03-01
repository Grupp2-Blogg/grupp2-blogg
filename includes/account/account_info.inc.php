<h3><?= $_SESSION['user']['username'] ?></h3>

<ul class="acc-info-ul">
    <li>
        <span class="acc-info-label">AnvändarID:</span>
        [<span class="acc-info-value"><?= htmlspecialchars((string)($_SESSION['user']['id'])) ?></span>]
    </li>
    <li>
        <span class="acc-info-label">Användarnamn:</span>
        <span class="acc-info-value"><?= htmlspecialchars($_SESSION['user']['username']); ?></span>
    </li>
    <li>
        <span class="acc-info-label">Email:</span>
        <span class="acc-info-value"><?= htmlspecialchars($_SESSION['user']['email']); ?></span>
    </li>
    <li>
        <span class="acc-info-label">Förnamn:</span>
        <span class="acc-info-value"><?= htmlspecialchars($_SESSION['user']['firstname'] ?? ''); ?></span>
    </li>
    <li>
        <span class="acc-info-label">Efternamn:</span>
        <span class="acc-info-value"><?= htmlspecialchars($_SESSION['user']['lastname'] ?? ''); ?></span>
    </li>
    <li>
        <span class="acc-info-label">Kön:</span>
        <span class="acc-info-value"><?= htmlspecialchars($_SESSION['user']['gender'] ?? ''); ?></span>
    </li>
    <li>
        <span class="acc-info-label">Födelseår:</span>
        <span class="acc-info-value"><?= htmlspecialchars($_SESSION['user']['birthyear'] ?? ''); ?></span>
    </li>
    <li>
        <span class="acc-info-label">Konto skapat:</span>
        <span class="acc-info-value"><?= htmlspecialchars($_SESSION['user']['created_at'] ?? ''); ?></span>
    </li>
</ul>

<form action="./account_redirect.php" method="get">
    <div class="form-button-container">

        <button type="submit" class="form-button" name="account-action" value="account-enter-edit">Redigera Uppgifter</button>
        <button type="submit" class="form-button" name="account-action" value="pw-enter-confirm-old">Ändra Lösenord</button>

    </div>
</form>