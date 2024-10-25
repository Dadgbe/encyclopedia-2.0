<?php
session_start();
require '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST['new_username'];
    $userID = $_POST['user_id'];

    // Обновление логина в базе данных
    $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
    if ($stmt->execute([$newUsername, $userID])) {
        $_SESSION['message'] = "Логин успешно обновлен.";
    } else {
        $_SESSION['message'] = "Ошибка при обновлении логина.";
    }

    header("Location: ../pages/profile.php");
    exit();
} else {
    echo "Некорректный запрос.";
}
?>
