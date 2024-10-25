<?php
session_start();
require '../config/config.php';

$articleId = $_GET['art'];
$stmt = $pdo->prepare("UPDATE articles SET status = 'Черновик' WHERE id = ?");
$stmt->execute([$articleId]);

header("Location: /pages/article.php?article={$articleId}");
exit;
?>