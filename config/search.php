<?php
require '../config/config.php';

$term = $_GET['term'] ?? '';

if (!empty($term)) {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE title LIKE :term AND status = \"Опубликовано\" OR content LIKE :contentTerm AND status = \"Опубликовано\"");
    $stmt->execute(['term' => "%$term%", 'contentTerm' => "%$term%"]);
    $results = $stmt->fetchAll();

    if ($results) {
        echo '<div class="rows-container">';

        foreach ($results as $index => $article) {
            if ($index % 3 === 0) {
                echo '<div class="row-art">';
            }
            $path_img = "/public/img/";
            $artimg = $article['image'];
            $previewContent = substr($article['content'], 0, 250);
            echo "<div class=\"article-block\">";

            if (!empty($artimg)) {
                echo "<img src=\"$path_img$artimg\"/>";
            }

            echo "<h3>{$article['title']}</h3>
                <div>{$previewContent}...</div>
                <a class=\"read-button\" href=\"../pages/article.php?article={$article['id']}\">Читать</a>
            </div>";
            if (($index + 1) % 3 === 0 || $index + 1 === count($results)) {
                echo '</div>';
            }
        }
        echo '</div>';
    } else {
        echo "Ничего не найдено. Попробуйте изменить запрос.";
    }
} else {
    echo "Пожалуйста, введите запрос для поиска.";
}
?>
