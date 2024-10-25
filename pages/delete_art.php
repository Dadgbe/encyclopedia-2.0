<?php
session_start();
require '../config/config.php';

$articleId = $_GET['art'];
$stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
$stmt->execute([$articleId]);

header("Location: /public/index.php");
exit;
?>