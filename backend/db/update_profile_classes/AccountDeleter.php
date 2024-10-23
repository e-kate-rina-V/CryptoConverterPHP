<?php
class AccountDeleter
{
    private $userId;
    private $connection;

    public function __construct($userId)
    {
        $this->userId = $userId;

        try {
            $this->connection = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            setValidationError('profile', 'Database connection error: ' . $ex->getMessage());
        }
    }

    public function process()
    {
        try {
            $statement = $this->connection->prepare("SELECT COUNT(*) FROM `users` WHERE `id` = :user_id");
            $statement->bindParam(':user_id', $this->userId);
            $statement->execute();

            if ($statement->fetchColumn() == 0) {
                setValidationError('profile', 'User not found.');

                return false;
            }

            $statement = $this->connection->prepare("DELETE FROM `users` WHERE `id` = :user_id");
            $statement->bindParam(':user_id', $this->userId);

            if ($statement->execute()) {
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
