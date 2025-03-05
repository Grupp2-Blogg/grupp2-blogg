<?php

declare(strict_types=1);
require_once '../config/session_config.php';
require_once '../models/User.php';


class UserController
{

    private PDO $pdo;
    private User $user;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->user = new User($pdo);
        $this->checkAccountAction();
    }


    private function checkAccountAction()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ./login.php");
            exit;
        }

        try {

            $id = $_SESSION['user']['id'];

            // Om användaren tryckt någon av "EDIT"-knapparna, sätt session variabel.
            $this->GET_CHECK($id);


            $user = $this->user->getAllInfoByID($id);
            $errors = [];

            if (!$user) {

                $errors["invalid_fetch"] = "Kunde inte hämta användare";
                $_SESSION['errors_account'] = $errors;

                header("Location: ./login.php");
                exit;
            } else {

                unset($_SESSION['user']);

                $_SESSION['user'] = $user;
            }


            // Om användaren tryckt "Spara ändringar" knappen. Kör insert till databas(efter massa validering)
            $this->POST_CHECK($id);


            header("Location: ./account_details.php");
            // $pdo = null;
            exit;
        } catch (PDOException $e) {
            $stmt = null;
            $pdo = null;
            die("Query failed: " .  $e->getMessage());

            // error_log($e->getMessage(), 3, 'C:/xampp/htdocs/myCode/grupp2-blogg/error.log');
            // header("Location: ../public/error.php");
            exit;
        }
    }

    private function POST_CHECK(int $id) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['account-action'])) {

            $post_allowedValues = ['account-update', 'pw-confirm-old', 'pw-update'];

            if (in_array($_POST['account-action'], $post_allowedValues, true)) {

                if ($_POST['account-action'] === 'pw-confirm-old') {
                    $this->handlePwdConfirm($id);
                }
                if ($_POST['account-action'] === 'pw-update') {
                    $this->handlePwdUpdate($id);
                }
                if ($_POST['account-action'] === 'account-update') {
                    $this->handleAccInfoUpdate($id);
                }
            }
        }
    }

    private function GET_CHECK(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['account-action'])) {

            $get_allowedValues = ['account-enter-edit', 'pw-enter-confirm-old', 'account-enter-delete', 'account-destroy'];

            if (in_array($_GET['account-action'], $get_allowedValues, true)) {

                if ($_GET['account-action'] === 'account-destroy') {
                    $this->user->delete($id);
                }

                $_SESSION['enter-edit'] = $_GET['account-action'];

                header("Location: ./account_details.php");
                exit;
            }
        }
    }

    private function handlePwdConfirm(int $id)
    {

        $old_pwd = trim($_POST['pw-confirm-old']);

        if (empty($old_pwd)) {

            $errors['no_input'] = "Fyll i lösenord";
        }
        $user = $this->user->auth_PwdUpdate($id, $old_pwd);
        if ($user === false) {

            $errors['errors_confirm'] = "Fel lösenord - försök igen";
        }
        if ($user === NULL) {

            $errors["invalid_fetch"] = "Kunde inte hämta användare";
        }

        if (!empty($errors)) {

            $_SESSION['errors_account'] = $errors;
            header("Location: ./account_details.php");
            exit;
        } else {

            unset($user);

            if (isset($_SESSION['delete-pw-confirm']) && $_SESSION['delete-pw-confirm'] === 'true') {
                $_SESSION['enter-edit'] = 'account-enter-destroy';
            } else {

                $_SESSION['enter-edit'] = 'pw-enter-edit';
            }
            header("Location: ./account_details.php");
            exit;
        }
    }



    private function handlePwdUpdate(int $id)
    {

        $new_pwd = trim($_POST['pw-update']);
        $repeat_pwd = trim($_POST['pw-update-repeat']);

        if ((empty($new_pwd) || empty($repeat_pwd))) {

            $errors['no_input'] = "Fyll i båda fälten!";
        }
        if ($new_pwd !== $repeat_pwd) {

            $errors['pw_mismatch'] = "Lösenorden matchar inte, försök igen";
        }

        if (!empty($errors)) {

            $_SESSION['errors_account'] = $errors;
            header("Location: ./account_details.php");
            exit;
        } else {

            $this->user->updateUserPwd($id, $new_pwd);
            unset($new_pwd);
            unset($repeat_pwd);
            $_SESSION['pwd_update_complete'] = "Lösenord uppdaterat!";
            unset($_SESSION['enter-edit']);
            header("Location: ./account_details.php");
            // $pdo = null;
            die();
        }
    }

    private function handleAccInfoUpdate(int $id)
    {

        $username = trim($_POST['username']);
        $email = trim($_POST['email']);

        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $gender = trim($_POST['gender']);
        $birthyear = trim($_POST['birthyear']);


        $firstname = !empty($firstname) ? $firstname : null;

        $lastname = !empty($lastname) ? $lastname : null;

        $birthyear = empty($birthyear) ? null : (int)$birthyear;


        $gender = !empty($gender) ? $gender : null;

        if ((empty($username) || empty($email))) {

            $errors["no_input"] = "Fyll i de obligatoriska fälten!";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["invalid_email"] = "Ogiltigt format på email!";
        }

        if ($_SESSION['user']['email'] !== $email) {

            if ($this->user->existsByEmail($email)) {

                $errors["email_taken"] = "Email-adressen är redan tagen!";
            }
        }
        if ($_SESSION['user']['username'] !== $username) {

            if ($this->user->existsByUsername($username)) {
                $errors["username_taken"] = "Användarnamnet är redan taget!";
            }
        }

        if (!empty($errors)) {

            $_SESSION['errors_account'] = $errors;
            header("Location: ./account_details.php");
            exit;
        } else {

            $this->user->updateUserInfo($id, $username, $email, $firstname, $lastname, $gender, $birthyear);
            $updatedUser = $this->user->getAllInfoByID($id);

            if (!$updatedUser) {
                $errors["invalid_fetch"] = "Kunde inte hämta användare";
                $_SESSION['errors_account'] = $errors;
                header("Location: ./login.php");
                exit;
            } else {
                unset($_SESSION['enter-edit']);
                $_SESSION['user'] = $updatedUser;
                header("Location: ./account_details.php");
                // $pdo = null;
                die();
            }
        }
    }


    #region klara grejer
    private function login()
    {
        if (isset($_SESSION['user'])) {

            header("Location: ../../public/index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username']);
            $pwd = trim($_POST['pwd']);

            try {

                $this->handleLogin($username, $pwd);
            } catch (PDOException $e) {

                // $pdo = null;
                die("Query failed: " .  $e->getMessage());
                // error_log($e->getMessage(), 3, 'C:/xampp/htdocs/myCode/grupp2-blogg/error.log');
                // header("Location: ../public/error.php");
                exit;
            }
        } else {

            header("Location: ../../public/index.php");
            exit;
        }
    }

    private function handleLogin(string $username, string $pwd)
    {
        $errors = [];
        if (!(empty($username) || empty($pwd))) {

            $user = $this->user->auth_Login($username, $pwd);

            if (!$user) {

                $errors["invalid_login"] = "Inkorrekt användarnamn eller lösenord";
                $_SESSION['errors_login'] = $errors;

                header("Location: ../public/login.php");
                exit;
            } else {

                $_SESSION['user'] = $user;
                $_SESSION['recent_login'] = "true";
                header("Location: ../public/index.php");
                // $pdo = null;
                die();
            }
        }
    }


    private function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username']);
            $pwd = trim($_POST['pwd']);
            $pwd_repeat = trim($_POST['pwd-repeat']);
            $email = trim($_POST['email']);

            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            $gender = trim($_POST['gender']);
            $birthyear = trim($_POST['birthyear']);

            try {

                require_once '../app/config/dboconn.php';

                // ERROR- OCH VALIDERINGSFUNKTIONER
                $errors = $this->validateReqFields($username, $pwd, $pwd_repeat, $email);

                if (!empty($errors)) {
                    $_SESSION['errors_signup'] = $errors;
                    header("Location: ../../public/signup.php");
                    exit;
                } else {
                    $_SESSION['user'] = ["id" => $user->create($pdo, $username, $pwd, $email, $firstname, $lastname, $gender, $birthyear)];
                    $_SESSION['signup'] = 'success';
                    header("Location: ../../public/login.php");
                    $pdo = null;
                    die();
                }
            } catch (PDOException $e) {
                die("Query failed: " .  $e->getMessage());
            }
        }
    }

    private function validateReqFields(string $username, string $pwd, string $pwd_repeat, string $email)
    {

        $errors = [];

        if (empty($username) || empty($pwd) || empty($email) || empty($pwd_repeat)) {
            $errors["no_input"] = "Fyll i de obligatoriska fälten!";
        }
        if ($pwd !== $pwd_repeat) {

            $errors['pw_mismatch'] = "Lösenorden matchar inte, försök igen";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["invalid_email"] = "Ogiltigt format på email!";
        }
        if ($this->user->existsByUsername($username)) {
            $errors["username_taken"] = "Användarnamnet är redan taget!";
        }
        if ($this->user->existsByEmail($email)) {
            $errors["email_taken"] = "Email-adressen är redan tagen!";
        }
        if (!isset($_POST['tc'])) {
            $errors["tc_nocheck"] = "Du behöver acceptera villkoren!";
        }

        return $errors;
    }
    #endregion
}
