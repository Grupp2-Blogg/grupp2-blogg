<?php

declare(strict_types=1);

require_once '../config/session_config.php';
require_once '../models/UserModel.php';
// require_once './helperFunctions.php';


class SignupController
{

    private PDO $pdo;
    private User $userModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->userModel = new User($this->pdo);
    }

    public function registerUser(): bool
    {

        $username = trim($_POST['username']);
        $pwd = trim($_POST['pwd']);
        $pwd_repeat = trim($_POST['pwd-repeat']);
        $email = trim($_POST['email']);

        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $gender = trim($_POST['gender']);
        $birthyear = trim($_POST['birthyear']);

        $birthyear = $birthyear === '' ? null : (int)$birthyear;

        $errors = $this->validateReqFields($username, $pwd, $pwd_repeat, $email);

        if (!empty($errors)) {
            $_SESSION['errors_signup'] = $errors;
            header("Location: ../public/signup.php");
            exit;
        } else {
            $this->userModel->create($username, $pwd, $email, $firstname, $lastname, $gender, $birthyear);
            $_SESSION['signup'] = 'success';
            header("Location: ../public/login.php");
            die();
        }
    }

    private function validateReqFields(string $username, string $pwd, string $pwd_repeat, string $email): array
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
        if ($this->userModel->existsByUsername($username)) {
            $errors["username_taken"] = "Användarnamnet är redan taget!";
        }
        if ($this->userModel->existsByEmail($email)) {
            $errors["email_taken"] = "Email-adressen är redan tagen!";
        }
        if (!isset($_POST['tc'])) {
            $errors["tc_nocheck"] = "Du behöver acceptera villkoren!";
        }

        return $errors;
    }

    public function checkForSignupErrors()
    {

        if (isset($_SESSION['errors_signup'])) {

            $errors = $_SESSION['errors_signup'];

            echo "<br>";

            foreach ($errors as $error) {
                echo '<p class="error-msg">' . $error  . '</p>';
            }

            unset($_SESSION['errors_signup']);
        }
    }

    public function displayYearSelect(int $yearsback)
    {

        $currentYear = date("Y");
        $earliestYear = $currentYear - $yearsback;

        for ($i = $currentYear; $i >= $earliestYear; $i--) {

            echo "<option value='{$i}'>{$i}</option>";
        }
    }
}
