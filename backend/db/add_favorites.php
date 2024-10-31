<?php
include_once __DIR__ . '/config.php';
require_once __DIR__ . '/../src/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'] ?? 0;
    $cryptoId = isset($_POST['crypto_id']) ? intval($_POST['crypto_id']) : 0;
    $action = $_POST['action'] ?? '';

    if ($userId > 0 && $cryptoId > 0) {
        try {
            $pdo = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($action === 'add') {
                $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM `favorites` WHERE `user_id` = :user_id AND `crypto_id` = :crypto_id");
                $checkStmt->execute(['user_id' => $userId, 'crypto_id' => $cryptoId]);
                $exists = $checkStmt->fetchColumn();

                if (!$exists) {
                    $statement = $pdo->prepare("INSERT INTO `favorites` (`user_id`, `crypto_id`) VALUES (:user_id, :crypto_id)");
                    $statement->execute(['user_id' => $userId, 'crypto_id' => $cryptoId]);
                    $message = "Successfully added ✅";
                } 
            } elseif ($action === 'remove') {
                $statement = $pdo->prepare("DELETE FROM `favorites` WHERE `user_id` = :user_id AND `crypto_id` = :crypto_id");
                $statement->execute(['user_id' => $userId, 'crypto_id' => $cryptoId]);
                $message = "Successfully deleted ✅";
            }

            header("Location: ../pages/converter.php?message=" . urlencode($message));
            exit;
        } catch (PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
        }
    } else {
        echo "Некорректный ввод.";
    }
}
