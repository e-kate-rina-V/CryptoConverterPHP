<?php
require_once __DIR__ . '/../validation_class/Validation.php';

class NameValidator extends Validation
{
    public function validate(): bool
    {
        if (empty($this->data)) {
            setValidationError('name', 'Incorrect name specified');
            return false;
        } else {
            try {
                $connection = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "SELECT COUNT(*) FROM `users` WHERE `name` = :name";
                $statement = $connection->prepare($sql);
                $statement->execute([':name' => $this->data]);

                if ($statement->fetchColumn() > 0) {
                    setValidationError('name', 'A user with that name already exists');
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
