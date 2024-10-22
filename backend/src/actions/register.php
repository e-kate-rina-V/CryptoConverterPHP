<?php

require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../../db/config.php';

interface validatorInterface
{
    public function validate(): bool;
}

abstract class Validation implements validatorInterface
{
    public function __construct(protected $data) {}

    abstract public function validate(): bool;
}

class nameValidator extends Validation
{
    public function validate(): bool
    {
        if (empty($this->data)) {
            setValidationError('name', 'Incorrect name specified');
            return false;
        } else {
            try {
                $conn = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "SELECT COUNT(*) FROM `users` WHERE `name` = :name";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':name' => $this->data]);

                if ($stmt->fetchColumn() > 0) {
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

class emailValidator extends Validation
{
    public function validate(): bool
    {
        if (!filter_var($this->data, FILTER_VALIDATE_EMAIL)) {
            setValidationError('email', 'Incorrect email specified');
            return false;
        } else {
            try {
                $conn = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "SELECT COUNT(*) FROM `users` WHERE `email` = :email";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':email' => $this->data]);

                if ($stmt->fetchColumn() > 0) {
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

class dateValidator extends Validation
{
    public function validate(): bool
    {
        if (empty($this->data)) {
            setValidationError('date', 'Incorrect date specified');
            return false;
        }
        return true;
    }
}

class sexValidator extends Validation
{
    public function validate(): bool
    {
        if (empty($this->data)) {
            setValidationError('sex', 'Please, select gender');
            return false;
        }
        return true;
    }
}

class avatarValidator extends Validation
{
    public function validate(): bool
    {
        if (empty($this->data)) {
            setValidationError('avatar', 'Please, select an avatar');
            return false;
        } elseif (!empty($this->data)) {
            $types = ['image/jpeg', 'image/png'];
            if (!in_array($this->data['type'], $types)) {
                setValidationError('avatar', 'Incorrect type of avatar image');
                return false;
            }
            if (($this->data['size'] / 1000000) >= 1) {
                setValidationError('avatar', 'The image must be no larger than 1 MB.');
                return false;
            }
        }
        return true;
    }
}

class passwordValidator extends Validation
{
    protected $passwordConfirmation;

    public function __construct($data, $passwordConfirmation)
    {
        parent::__construct($data);
        $this->passwordConfirmation = $passwordConfirmation;
    }

    public function validate(): bool
    {
        $password = $this->data;

        if (empty($this->data)) {
            setValidationError('password', 'Password is empty');
            return false;
        } elseif (strlen($password) < 8) {
            setValidationError('password', 'The password must contain at least 8 characters');
            return false;
        } elseif (!preg_match('/[a-zA-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            setValidationError('password', 'The password must contain both letters and numbers');
            return false;
        } elseif ($this->data !== $this->passwordConfirmation) {
            setValidationError('password', 'Passwords do not match');
            return false;
        }
        return true;
    }
}


$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$date = $_POST['date'] ?? null;
$sex = $_POST['sex'] ?? null;
$avatar = $_FILES['avatar'] ?? null;
$password = $_POST['password'] ?? null;
$passwordConfirmation = $_POST['password_confirmation'] ?? null;


$nameValidator = new nameValidator($name);
$emailValidator = new emailValidator($email);
$dateValidator = new dateValidator($date);
$sexValidator = new sexValidator($sex);
$avatarValidator = new avatarValidator($avatar);
$passwordValidator = new passwordValidator($password, $passwordConfirmation);

if (
    $nameValidator->validate() && $emailValidator->validate() && $dateValidator->validate() &&
    $sexValidator->validate() && $avatarValidator->validate() && $passwordValidator->validate()
) {

    $avatarPath = uploadFile($avatar, 'avatar');

    $pdo = getPDO();

    $query = "INSERT INTO `users` (`name`, `email`, `avatar`, `date`, `sex`, `hash_password`) VALUES (:name, :email, :avatar, :date, :sex, :hash_password)";

    $params = [
        'name' => $name,
        'email' => $email,
        'avatar' => $avatarPath,
        'date' => $date,
        'sex' => $sex,
        'hash_password' => password_hash($password, PASSWORD_DEFAULT)
    ];

    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute($params);
    } catch (\Exception $e) {
        die($e->getMessage());
    }

    redirect('../../login.php');
} elseif (!empty($_SESSION['validation'])) {
    setOldValue('name', $name);
    setOldValue('email', $email);
    setOldValue('date', $date);
    setOldValue('sex', $sex);
    if (!empty($avatar) && $avatar['error'] === 0) {
        setOldValue('avatar', $avatar['name']);
    } else {
        setValidationError('avatar', 'Error uploading avatar');
    }
    redirect('../../register.php');
}
