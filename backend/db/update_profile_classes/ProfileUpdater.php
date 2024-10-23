<?php
abstract class ProfileUpdater
{
    protected $connection;
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->connection = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    abstract protected function validate(): bool;
    abstract protected function update(): bool;

    public function process()
    {
        if ($this->validate()) {
            if ($this->update()) {
                header("Location: ../pages/profileinfo.php");
                exit();
            } else {
                $this->setValidationError('profile', 'Failed to update.');
            }
        }

        $_SESSION['validation_errors'] = $_SESSION['validation'];
        header("Location: ../pages/profileinfo.php");
        exit();
    }

    protected function setValidationError($field, $message)
    {
        setValidationError($field, $message);
    }
}
