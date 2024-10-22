<?php

require_once __DIR__ . '/../helpers.php';

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
        $user = findUser($this->data);

        if (empty($this->data) || !is_string($this->data)) {
            setValidationError('name', 'Please, enter your name');
            return false;
        } elseif (!$user) {
            setMessage('error', "User $this->data not found");
            return false;
        }
        return true;
    }
}

class passwordValidator extends Validation
{
    protected $user;

    public function __construct($data, $user)
    {
        parent::__construct($data);
        $this->user = $user;
    }

    public function validate(): bool
    {
        if (!password_verify($this->data, $this->user['hash_password'])) {
            setMessage('error', 'Empty or incorrect password');
            return false;
        }
        return true;
    }
}

$rememberedName = $_COOKIE['remember_name'] ?? '';
$rememberedPassword = '';

$name = $_POST['name'] ?? null;
$password = $_POST['password'] ?? null;
$rememberMe = $_POST['remember_me'] ?? null;

$user = findUser($name);

$nameValidator = new nameValidator($name);
$passwordValidator = new passwordValidator($password, $user);

if ($nameValidator->validate() && $passwordValidator->validate()) {
    $_SESSION['user_id'] = $user['id'];

    setcookie('remember_name', $name, time() + (86400 * 30), "/");

    if ($rememberMe) {
        $token = bin2hex(random_bytes(16));

        $pdo = getPDO();
        $query = "UPDATE users SET remember_token = :token WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['token' => $token, 'id' => $user['id']]);

        setcookie('remember_token', $token, time() + (86400 * 30), "/");
    }
    redirect('../../index.php');
} else {
    if (!empty($_SESSION['validation'])) {
        setOldValue('name', $name);
    }
    redirect('../../login.php');
}

if (!isset($_SESSION['user']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    $pdo = getPDO();
    $query = "SELECT * FROM `users` WHERE `remember_token` = :token";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user['id'];
        redirect('../../index.php');
    }
}
