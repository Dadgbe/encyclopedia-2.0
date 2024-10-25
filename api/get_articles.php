<?php
require '../config/config.php';
session_start();

$type = $_GET['type'];
$response = '';
$user_id = $_SESSION['user_id'];

switch ($type) {
    case 'published_articles':
        $query = "SELECT * FROM articles WHERE status = 'Опубликовано' AND author_id = $user_id ORDER BY created_at DESC";
        break;
    case 'draft_articles':
        $query = "SELECT * FROM articles WHERE status = 'Черновик' AND author_id = $user_id ORDER BY created_at DESC";
        break;
    case 'review_art':
        $query = "SELECT * FROM articles WHERE status = 'На рецензии' ORDER BY created_at DESC";
        break;
    case 'reviews_articles':
        $query = "SELECT * FROM articles WHERE status = 'На рецензии' AND author_id = $user_id ORDER BY created_at DESC";
        break;
    default:
        echo "Invalid type";
        exit;
}

$stmt = $pdo->query($query);
$articles = $stmt->fetchAll();

foreach ($articles as $index => $article) {
    if ($index % 3 === 0) {
        $response .= '<div class="row-art">';
    }

    $path_img = "/public/img/";
    $artimg = $article['image'];
    $fullContent = implode(' ', [
        $article['organizational'],
        $article['economic'],
        $article['marketing'],
        $article['phisics'],
        $article['technical'],
        $article['mathematical'],
        $article['normative'],
        $article['pravo'],
        $article['constitutional'],
        $article['socialComputer']
    ]);
    $fullContent = str_replace('<br>', ' ', $fullContent);

        $previewContent = iconv('windows-1251','UTF-8', iconv('UTF-8','windows-1251', $fullContent));

    $response .= "<div class=\"article-block\">";
    if (!empty($artimg)) {
        $response .= "<img src=\"$path_img$artimg\"/>";
    }

    $response .= "<h3>{$article['title']}</h3>
        <div>{$previewContent}...</div>
        <a class=\"read-button\" href=\"../pages/article.php?article={$article['id']}\">Читать</a>";

    if ($type == 'review_art') {
        $response .= "<a class=\"approve-button\" href=\"approve_article.php?id={$article['id']}\">Одобрить</a>
                      <a class=\"reject-button\" href=\"reject_article.php?id={$article['id']}\">Отправить на доработку</a>";
    }

    $response .= "</div>";

    if (($index + 1) % 3 === 0 || $index + 1 === count($articles)) {
        $response .= '</div>';
    }
}

echo $response;
?>
