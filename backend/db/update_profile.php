<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/update_profile_classes/NameUpdater.php';
require_once __DIR__ . '/update_profile_classes/EmailUpdater.php';
require_once __DIR__ . '/update_profile_classes/PasswordUpdater.php';
require_once __DIR__ . '/update_profile_classes/AccountDeleter.php';

checkAuth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];

    if (isset($_POST['new_name'])) {
        $updater = new NameUpdater($userId, $_POST['new_name']);
    } elseif (isset($_POST['new_email'])) {
        $updater = new EmailUpdater($userId, $_POST['new_email']);
    } elseif (isset($_POST['password'])) {
        $updater = new PasswordUpdater($userId, $_POST['password'], $_POST['new_password'], $_POST['confirm_password']);
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        if (isset($_POST['delete_confirm']) && $_POST['delete_confirm'] == 1) {

            $updater = new AccountDeleter($userId);
            if ($updater->process()) {
                header("Location: ../pages/login.php");
                exit();
            } else {
                header("Location: ../pages/profileinfo.php?error=delete_failed");
                exit();
            }
        } else {
            setValidationError('profile', 'You must confirm the deletion by selecting "Yes".');
            header("Location: ../pages/profileinfo.php");
            exit();
        }
    } else {
        setValidationError('profile', 'Unknown action or no confirmation for deletion.');
        header("Location: ../pages/profileinfo.php");
        exit();
    }

    if (isset($updater)) {
        $updater->process();
    }
}
