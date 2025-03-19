

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

<div class="form-button-container-a">
    <a href="accountDetails.php?mode=account-enter-edit" class="form-button-a">Redigera Konto</a>
    <a href="accountDetails.php?mode=pw-enter-confirm-old" class="form-button-a">Ändra Lösenord</a>
    <a href="accountDetails.php?mode=account-enter-delete" class="form-button-a">Radera Konto</a>

</div>