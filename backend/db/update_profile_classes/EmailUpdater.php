<?php
require_once __DIR__ . '/ProfileUpdater.php';

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
        $statement = $this->connection->prepare($sql);
        $statement->execute([':email' => $this->newEmail]);

        if ($statement->fetchColumn() > 0) {
            $this->setValidationError('email', 'A user with that email already exists.');
            return false;
        }

        return true;
    }

    protected function update(): bool
    {
        $sql = "UPDATE `users` SET `email` = :new_email WHERE `id` = :user_id";
        $statement = $this->connection->prepare($sql);
        return $statement->execute([':new_email' => $this->newEmail, ':user_id' => $this->userId]);
    }
}
