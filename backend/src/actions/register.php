<?php

require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../../db/config.php';

require_once __DIR__ . '/register_classes/NameValidator.php';
require_once __DIR__ . '/register_classes/EmailValidator.php';
require_once __DIR__ . '/register_classes/DateValidator.php';
require_once __DIR__ . '/register_classes/SexValidator.php';
require_once __DIR__ . '/register_classes/AvatarValidator.php';
require_once __DIR__ . '/register_classes/PasswordValidator.php';


$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$date = $_POST['date'] ?? null;
$sex = $_POST['sex'] ?? null;
$avatar = $_FILES['avatar'] ?? null;
$password = $_POST['password'] ?? null;
$passwordConfirmation = $_POST['password_confirmation'] ?? null;


$nameValidator = new NameValidator($name);
$emailValidator = new EmailValidator($email);
$dateValidator = new DateValidator($date);
$sexValidator = new SexValidator($sex);
$avatarValidator = new AvatarValidator($avatar);
$passwordValidator = new PasswordValidator($password, $passwordConfirmation);

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

    $statement = $pdo->prepare($query);

    try {
        $statement->execute($params);
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
