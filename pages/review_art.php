<?php
session_start();
require '../config/config.php';


if ($_SESSION['role'] != 1 && $_SESSION['role'] != 2) {
    echo "Нет доступа.";
    exit;
}


$stmt = $pdo->query("SELECT id, title, content FROM articles WHERE status = 'На рецензии'");
$articles = $stmt->fetchAll();
?>

<h2>Статьи на рецензии</h2>
<?php foreach ($articles as $article): ?>
    <div>
        <h3><?= htmlspecialchars($article['title']) ?></h3>
        <p><?= htmlspecialchars($article['content']) ?></p>
        <a href="approve_article.php?id=<?= $article['id'] ?>">Одобрить</a>
        <a href="reject_article.php?id=<?= $article['id'] ?>">Отправить на доработку</a>
    </div>

<?php endforeach; ?>

echo '<script src="../scripts/script.js"></script>';