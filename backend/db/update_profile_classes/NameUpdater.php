<?php
require_once __DIR__ . '/ProfileUpdater.php';

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
        $statement = $this->connection->prepare($sql);
        $statement->execute([':name' => $this->newName]);

        if ($statement->fetchColumn() > 0) {
            $this->setValidationError('name', 'A user with that name already exists.');
            return false;
        }

        return true;
    }

    protected function update(): bool
    {
        $sql = "UPDATE `users` SET `name` = :new_name WHERE `id` = :user_id";
        $statement = $this->connection->prepare($sql);
        return $statement->execute([':new_name' => $this->newName, ':user_id' => $this->userId]);
    }
}
