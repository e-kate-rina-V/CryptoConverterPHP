<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../src/helpers.php';

checkAuth();

abstract class ProfileUpdater
{
    protected $conn;
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->conn = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    abstract protected function validate(): bool;
    abstract protected function update(): bool;

    public function process()
    {
        if ($this->validate()) {
            if ($this->update()) {
                header("Location: ../profileinfo.php");
                exit();
            } else {
                $this->setValidationError('profile', 'Failed to update.');
            }
        }

        $_SESSION['validation_errors'] = $_SESSION['validation'];
        header("Location: ../profileinfo.php");
        exit();
    }

    protected function setValidationError($field, $message)
    {
        setValidationError($field, $message);
    }
}

class NameUpdater extends ProfileUpdater
{
    private $newName;

    public function __construct($userId, $newName)
    {
        parent::__construct($userId);
        $this->newName = $newName;
    }

    protected function validate(): bool
    {
        if (empty($this->newName)) {
            $this->setValidationError('name', 'Name cannot be empty.');
            return false;
        }

        $sql = "SELECT COUNT(*) FROM `users` WHERE `name` = :name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':name' => $this->newName]);

        if ($stmt->fetchColumn() > 0) {
            $this->setValidationError('name', 'A user with that name already exists.');
            return false;
        }

        return true;
    }

    protected function update(): bool
    {
        $sql = "UPDATE `users` SET `name` = :new_name WHERE `id` = :user_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':new_name' => $this->newName, ':user_id' => $this->userId]);
    }
}

class EmailUpdater extends ProfileUpdater
{
    private $newEmail;

    public function __construct($userId, $newEmail)
    {
        parent::__construct($userId);
        $this->newEmail = $newEmail;
    }

    protected function validate(): bool
    {
        if (empty($this->newEmail)) {
            $this->setValidationError('email', 'Email cannot be empty.');
            return false;
        }

        if (!filter_var($this->newEmail, FILTER_VALIDATE_EMAIL)) {
            $this->setValidationError('email', 'Incorrect email specified.');
            return false;
        }

        $sql = "SELECT COUNT(*) FROM `users` WHERE `email` = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $this->newEmail]);

        if ($stmt->fetchColumn() > 0) {
            $this->setValidationError('email', 'A user with that email already exists.');
            return false;
        }

        return true;
    }

    protected function update(): bool
    {
        $sql = "UPDATE `users` SET `email` = :new_email WHERE `id` = :user_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':new_email' => $this->newEmail, ':user_id' => $this->userId]);
    }
}

class PasswordUpdater extends ProfileUpdater
{
    private $password;
    private $newPassword;
    private $confirmPassword;

    public function __construct($userId, $password, $newPassword, $confirmPassword)
    {
        parent::__construct($userId);
        $this->password = $password;
        $this->newPassword = $newPassword;
        $this->confirmPassword = $confirmPassword;
    }

    protected function validate(): bool
    {
        $stmt = $this->conn->prepare("SELECT `hash_password` FROM `users` WHERE `id` = :user_id");
        $stmt->execute([':user_id' => $this->userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($this->password, $user['hash_password'])) {
            $this->setValidationError('password', 'Incorrect current password.');
            return false;
        }

        if (empty($this->newPassword)) {
            $this->setValidationError('password', 'New password cannot be empty.');
            return false;
        }

        if (strlen($this->newPassword) < 8) {
            $this->setValidationError('password', 'New password must be at least 8 characters long.');
            return false;
        }
        if (!preg_match('/[a-zA-Z]/', $this->newPassword) || !preg_match('/[0-9]/', $this->newPassword)) {
            $this->setValidationError('password', 'The password must contain both letters and numbers');
            return false;
        }
        if ($this->newPassword !== $this->confirmPassword) {
            $this->setValidationError('password', 'Passwords do not match.');
            return false;
        }

        return true;
    }

    protected function update(): bool
    {
        $newPasswordHash = password_hash($this->newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE `users` SET `hash_password` = :new_password WHERE `id` = :user_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':new_password' => $newPasswordHash, ':user_id' => $this->userId]);
    }
}

class AccountDeleter
{
    private $userId;
    private $conn;

    public function __construct($userId)
    {
        $this->userId = $userId;

        try {
            $this->conn = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            setValidationError('profile', 'Database connection error: ' . $ex->getMessage());
        }
    }

    public function process()
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM `users` WHERE `id` = :user_id");
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->execute();

            if ($stmt->fetchColumn() == 0) {
                setValidationError('profile', 'User not found.');
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM `users` WHERE `id` = :user_id");
            $stmt->bindParam(':user_id', $this->userId);

            if ($stmt->execute()) {
                session_destroy();
                return true;
            } else {
                setValidationError('profile', 'Failed to delete account.');
                return false;
            }
        } catch (PDOException $ex) {
            setValidationError('profile', 'Error: ' . $ex->getMessage());
            return false;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];

    if (isset($_POST['new_name'])) {
        $updater = new NameUpdater($userId, $_POST['new_name']);
    } elseif (isset($_POST['new_email'])) {
        $updater = new EmailUpdater($userId, $_POST['new_email']);
    } elseif (isset($_POST['password'])) {
        $updater = new PasswordUpdater($userId, $_POST['password'], $_POST['new_password'], $_POST['confirm_password']);
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        if (isset($_POST['delete_confirm']) && $_POST['delete_confirm'] == 1) {

            $updater = new AccountDeleter($userId);
            if ($updater->process()) {
                header("Location: ../login.php");
                exit();
            } else {
                header("Location: ../profileinfo.php?error=delete_failed");
                exit();
            }
        } else {
            setValidationError('profile', 'You must confirm the deletion by selecting "Yes".');
            header("Location: ../profileinfo.php");
            exit();
        }
    } else {
        setValidationError('profile', 'Unknown action or no confirmation for deletion.');
        header("Location: ../profileinfo.php");
        exit();
    }

    if (isset($updater)) {
        $updater->process();
    }
}
