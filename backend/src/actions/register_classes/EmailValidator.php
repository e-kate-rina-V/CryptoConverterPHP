<?php
require_once __DIR__ . '/../validation_class/Validation.php';

class EmailValidator extends Validation
{
    public function validate(): bool
    {
        if (!filter_var($this->data, FILTER_VALIDATE_EMAIL)) {
            setValidationError('email', 'Incorrect email specified');
            return false;
        } else {
            try {
                $connection = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "SELECT COUNT(*) FROM `users` WHERE `email` = :email";
                $statement = $connection->prepare($sql);
                $statement->execute([':email' => $this->data]);

                if ($statement->fetchColumn() > 0) {
                    setValidationError('email', 'A user with this email already exists');
                    return false;
                }
            } catch (PDOException $ex) {
                echo "Connection failed: " . $ex->getMessage();
                return false;
            }
        }
        return true;
    }
}
