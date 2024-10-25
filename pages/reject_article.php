<?php
session_start();
require '../config/config.php';

if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) {
    $articleId = $_GET['id'];
    $stmt = $pdo->prepare("UPDATE articles SET status = 'Черновик' WHERE id = ?");
    $stmt->execute([$articleId]);

    header("Location: profile.php");
    exit;
} else {
    echo "Нет доступа.";
}
?>

