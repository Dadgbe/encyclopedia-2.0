В<?php
session_start();
require '../config/config.php';

$articleId = $_GET['art'];
if ($_SESSION['role'] === 1) {
        $stmt = $pdo->prepare("UPDATE articles SET status = 'Опубликовано' WHERE id = ?");
        $stmt->execute([$articleId]);

    } else if ($_SESSION['role'] === 3 || $_SESSION['role'] === 2) {
        $stmt = $pdo->prepare("UPDATE articles SET status = 'На рецензии' WHERE id = ?");
        $stmt->execute([$articleId]);

    }


header("Location: /pages/article.php?article={$articleId}");
exit;
?>