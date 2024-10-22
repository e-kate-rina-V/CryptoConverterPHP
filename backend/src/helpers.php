<?php

session_start();

require_once __DIR__ . '/../db/config.php';

function redirect(string $path)
{
    header("Location: $path");
    die();
}

function setValidationError(string $fieldName, string $message): void
{
    $_SESSION['validation'][$fieldName] = $message;
}

function hasValidationError(string $fieldName): bool
{
    return isset($_SESSION['validation'][$fieldName]);
}

function validationErrorAttr(string $fieldName): string
{
    return isset($_SESSION['validation'][$fieldName]) ? 'aria-invalid="true"' : '';
}

function validationErrorMessage(string $fieldName): string
{
    $message = $_SESSION['validation'][$fieldName] ?? '';
    unset($_SESSION['validation'][$fieldName]);
    return $message;
}

function setOldValue(string $key, mixed $value): void
{
    $_SESSION['old'][$key] = $value;
}

function old(string $key)
{
    $value = $_SESSION['old'][$key] ?? '';
    unset($_SESSION['old'][$key]);
    return $value;
}

function uploadFile(array $file, string $prefix = ''): string
{
    $uploadPath = __DIR__ . '/../uploads';

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = $prefix . '_' . time() . ".$ext";

    if (!move_uploaded_file($file['tmp_name'], "$uploadPath/$fileName")) {
        die('Ошибка при загрузке файла на сервер');
    }

    return "uploads/$fileName";
}

function setMessage(string $key, string $message): void
{
    $_SESSION['message'][$key] = $message;
}

function hasMessage(string $key): bool
{
    return isset($_SESSION['message'][$key]);
}

function getMessage(string $key): string
{
    $message = $_SESSION['message'][$key] ?? '';
    unset($_SESSION['message'][$key]);
    return $message;
}

function getPDO(): PDO
{
    try {
        return new \PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8;dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
    } catch (\PDOException $e) {
        die("Connection error: {$e->getMessage()}");
    }
}

function findUser(string $name): array|bool
{
    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name");
    $stmt->execute(['name' => $name]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}


function currentUser(): array|false
{
    $pdo = getPDO();

    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $userId]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}


function logout(): void
{
    $_SESSION = [];

    session_destroy();

    unset($_SESSION['user_id']);

    if (isset($_COOKIE['remember_name'])) {
        setcookie('remember_name', '', time() - 3600, "/");
    }

    if (!isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, "/");
    }

    if (isset($_COOKIE['cookies_accepted'])) {
        setcookie('cookies_accepted', '', time() - 3600, "/");
    }

    redirect('../../login.php');
}


function checkAuth(): void
{
    if (!isset($_SESSION['user_id'])) {
        redirect('../../');
    }
}


function checkGuest(): void
{
    if (isset($_SESSION['user']['id'])) {
        redirect('../../index.php');
    }
}


// Трейти

class Greet
{
    public function greeting()
    {
        echo 'crypto converter';
    }
}

trait FirstGreeting
{
    public function greeting()
    {
        echo 'Glad to see you ';
        parent::greeting();
    }
}

trait SecondGreeting
{
    public function greeting()
    {
        echo 'Welcome to ';
        parent::greeting();
    }
}

class Greeting extends Greet
{
    use FirstGreeting, SecondGreeting {
        SecondGreeting::greeting insteadof FirstGreeting;
    }
}

$g = new Greeting();
