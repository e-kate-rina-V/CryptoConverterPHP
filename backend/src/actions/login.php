<?php

require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/login_classes/NameValidator.php';
require_once __DIR__ . '/login_classes/PasswordValidator.php';


$rememberedName = $_COOKIE['remember_name'] ?? '';
$rememberedPassword = '';

$name = $_POST['name'] ?? null;
$password = $_POST['password'] ?? null;
$rememberMe = $_POST['remember_me'] ?? null;

$user = findUser($name);

$nameValidator = new NameValidator($name);
$passwordValidator = new PasswordValidator($password, $user);

if ($nameValidator->validate() && $passwordValidator->validate()) {
    $_SESSION['user_id'] = $user['id'];

    setcookie('remember_name', $name, time() + (86400 * 30), "/");

    if ($rememberMe) {
        $token = bin2hex(random_bytes(16));

        $pdo = getPDO();
        $query = "UPDATE users SET remember_token = :token WHERE id = :id";
        $statement = $pdo->prepare($query);
        $statement->execute(['token' => $token, 'id' => $user['id']]);

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
    $statement = $pdo->prepare($query);
    $statement->execute(['token' => $token]);
    $user = $statement->fetch();

    if ($user) {
        $_SESSION['user'] = $user['id'];
        redirect('../../index.php');
    }
}
