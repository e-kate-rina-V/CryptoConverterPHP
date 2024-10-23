<?php
require_once __DIR__ . '/ProfileUpdater.php';

class PasswordUpdater extends ProfileUpdater
{
    public function __construct($userId, private $password, private $newPassword, private $confirmPassword)
    {
        parent::__construct($userId);
    }

    protected function validate(): bool
    {
        $statement = $this->connection->prepare("SELECT `hash_password` FROM `users` WHERE `id` = :user_id");
        $statement->execute([':user_id' => $this->userId]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

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
        $statement = $this->connection->prepare($sql);
        
        return $statement->execute([':new_password' => $newPasswordHash, ':user_id' => $this->userId]);
    }
}
