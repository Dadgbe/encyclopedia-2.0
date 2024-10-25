<?php
require '../config/config.php';

$content = '';

// Загрузка всех опубликованных статей
$stmt = $pdo->query("SELECT * FROM articles WHERE status = 'Опубликовано'");
$articles = $stmt->fetchAll();

if ($articles) {
    $content .= '<div class="rows-container">';
    foreach ($articles as $index => $article) {
        if ($index % 3 === 0) {
            $content .= '<div class="row-art">';
        }

        $path_img = "/public/img/";
        $artimg = $article['image'];
        $previewContent = substr($article['content'], 0, 250);
        $content .= "<div class=\"article-block\"><img src=\"$path_img$artimg\"/>
            <h3>{$article['title']}</h3>
            <div>{$previewContent}...</div>
            <a class=\"read-button\" href=\"../pages/article.php?article={$article['id']}\">Читать</a>
        </div>";

        if (($index + 1) % 3 === 0 || $index + 1 === count($articles)) {
            $content .= '</div>'; // Закрытие .row-art
        }
    }
    $content .= '</div>'; // Закрытие .rows-container
} else {
    $content = "Статей в базе данных не найдено.";
}

include '../templates/template.php';
?>
