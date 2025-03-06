<?php

declare(strict_types=1);
require_once './UserModel.php';

class AccountDetailsController
{
    private PDO $pdo;
    private UserModel $userModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->userModel = new UserModel($this->pdo);
    }

    /**
     * Uppdaterar användarens konto.
     */
    public function updateAccount(int $id): void
    {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $firstname = trim($_POST['firstname']) ?: null;
        $lastname = trim($_POST['lastname']) ?: null;
        $gender = trim($_POST['gender']) ?: null;
        $birthyear = trim($_POST['birthyear']);
        $birthyear = $birthyear === '' ? null : (int)$birthyear;

        $errors = [];

        // Validering
        if (empty($username) || empty($email)) {
            $errors["no_input"] = "Fyll i de obligatoriska fälten!";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["invalid_email"] = "Ogiltigt format på email!";
        }

        // Kolla om e-post eller användarnamn redan finns
        if ($_SESSION['user']['email'] !== $email && $this->userModel->existsByEmail($email)) {
            $errors["email_taken"] = "Email-adressen är redan tagen!";
        }
        if ($_SESSION['user']['username'] !== $username && $this->userModel->existsByUsername($username)) {
            $errors["username_taken"] = "Användarnamnet är redan taget!";
        }

        // Om fel uppstått, skicka tillbaka användaren
        if (!empty($errors)) {
            $_SESSION['errors_account'] = $errors;
            header("Location: ./account_details.php?mode=account-enter-edit");
            exit;
        }

        // Uppdatera användarinfo
        $this->userModel->updateUserInfo($id, $username, $email, $firstname, $lastname, $gender, $birthyear);

        // Hämta uppdaterad info
        $updatedUser = $this->userModel->getAllInfoByID($id);
        if (!$updatedUser) {
            $_SESSION['errors_account'] = ["invalid_fetch" => "Kunde inte hämta användare"];
            header("Location: ./login.php");
            exit;
        }

        // Uppdatera sessionen och skicka tillbaka användaren
        $_SESSION['user'] = $updatedUser;
        header("Location: ./account_details.php");
        exit;
    }

    /**
     * Uppdaterar lösenordet efter att gammalt lösenord har bekräftats.
     */
    public function updatePassword(int $id): void
    {
        $newPwd = trim($_POST['pw-update']);
        $repeatPwd = trim($_POST['pw-update-repeat']);
        $errors = [];

        if (empty($newPwd) || empty($repeatPwd)) {
            $errors['no_input'] = "Fyll i båda fälten!";
        } elseif ($newPwd !== $repeatPwd) {
            $errors['pw_mismatch'] = "Lösenorden matchar inte, försök igen";
        }

        if (!empty($errors)) {
            $_SESSION['errors_account'] = $errors;
            header("Location: ./account_details.php?mode=pw-enter-edit");
            exit;
        }

        // Uppdatera lösenord
        $this->userModel->updateUserPwd($id, $newPwd);
        $_SESSION['pwd_update_complete'] = "Lösenord uppdaterat!";
        header("Location: ./account_details.php");
        exit;
    }

    /**
     * Bekräftar användarens gamla lösenord innan lösenord eller konto kan ändras.
     */
    public function confirmOldPassword(int $id): void
    {
        $oldPwd = trim($_POST['pw-confirm-old']);
        $errors = [];

        if (empty($oldPwd)) {
            $errors['no_input'] = "Fyll i lösenord";
        }

        $user = $this->userModel->auth_PwdUpdate($id, $oldPwd);
        if ($user === false) {
            $errors['errors_confirm'] = "Fel lösenord - försök igen";
        } elseif ($user === null) {
            $errors["invalid_fetch"] = "Kunde inte hämta användare";
        }

        if (!empty($errors)) {
            $_SESSION['errors_account'] = $errors;
            header("Location: ./account_details.php?mode=pw-enter-confirm-old");
            exit;
        }

        $mode = '';
        // Om lösenord bekräftats, bestäm om det gäller lösenordsbyte eller kontoborttagning
        if (!empty($_SESSION['delete-pw-confirm']) && $_SESSION['delete-pw-confirm'] === 'true') {
            $mode = 'account-enter-destroy';
            unset($_SESSION['delete-pw-confirm']); // 🔹 Rensa värdet efter att det använts
        } else {
            $mode = 'pw-enter-edit';
        }

        header("Location: ./account_details.php?mode=" . $mode);
        exit;
    }

    /**
     * Tar bort användarens konto.
     */
    public function deleteAccount(int $id): void
    {
        $this->userModel->delete($id);
        session_destroy();
        header("Location: ./index.php");
        exit;
    }

    /**
     * Hämtar användarinformation.
     */
    public function getUserInfo(int $id): array
    {
        return $this->userModel->getAllInfoByID($id);
    }

    // Funktionen kollar vilket läge/format som account_details.php ska visas.
    public function checkEditMode()
    {
        if (!isset($_SESSION['user'])) {

            header("Location: ./login.php");
            exit;
        }

        $allowedValues = ['account-enter-edit', 'pw-enter-confirm-old', 'pw-enter-edit', 'account-enter-delete', 'account-enter-destroy', 'account-destroy'];

        // Hämta 'mode' från GET (om den finns), annars sätt den till standard ('account-info')
        $mode = $_GET['mode'] ?? 'account-info';

        if ($mode !== 'account-enter-destroy' && isset($_SESSION['delete-pw-confirm'])) {
            unset($_SESSION['delete-pw-confirm']);
        }

        if (in_array($mode, $allowedValues, true)) {
            switch ($mode) {
                case 'account-enter-edit':
                    require_once './includes_account/editform.inc.php';
                    break;
                case 'pw-enter-confirm-old':
                    require_once './includes_account/pw_confirm_old.inc.php';
                    break;
                case 'pw-enter-edit':
                    require_once './includes_account/pw_update.inc.php';
                    break;
                case 'account-enter-delete':
                    $_SESSION['delete-pw-confirm'] = 'true';
                    require_once './includes_account/pw_confirm_old.inc.php';
                    break;
                case 'account-enter-destroy':
                    require_once './includes_account/acc_enter_destroy.inc.php';
                    break;
                case 'account-destroy':
                    $_SESSION['account-deleted'] = 'true';
                    header("Location: ./index.php");
                    exit;
                default:
                    header("Location: ./error.php"); // Felaktigt mode, skicka till error-sida
                    exit;
            }
        } else {

            require_once './includes_account/account_info.inc.php'; // Standardvy om 'mode' saknas eller är ogiltigt
        }
    }
    // Funktionen kollar efter errors vid uppdatering av konto - och visar dem.
    public function checkAccountUpdateErrors()
    {

        if (isset($_SESSION['errors_account'])) {

            $errors = $_SESSION['errors_account'];
            unset($_SESSION['errors_account']);

            echo "<br>";

            foreach ($errors as $error) {
                echo '<p class="error-msg">' . $error  . '</p>';
            }
        }
    }
}
