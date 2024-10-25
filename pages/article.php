<?php
require '../config/config.php';
session_start();

$page = isset($_GET['article']) ? $_GET['article'] : null;

$update_stmt = $pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = :id");
$update_stmt->execute(['id' => $page]);

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
$stmt->execute(['id' => $page]);

$article = $stmt->fetch();

if ($article) {
    $category_stmt = $pdo->prepare("SELECT CategoryName FROM categories WHERE ID = ?");
    $category_stmt->execute([$article['category']]);
    $category = $category_stmt->fetch();
    $categoryName = $category['CategoryName'];
    $art_name = $article['title'];

    echo "<head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <title>{$art_name}</title>
    </head>";

    $path_img = "/public/img/";
    $path_files = "/uploads/";

    $author_id = $article['author_id'];
    $author_stmt = $pdo->prepare("SELECT username FROM users WHERE id = :author_id");
    $author_stmt->execute(['author_id' => $author_id]);
    $author_result = $author_stmt->fetch();
    $author_name = $author_result ? $author_result['username'] : 'Unknown';

    $created_at = date("d.m.Y H:i", strtotime($article['created_at']));

    $edit_buttons = '';
    if (isset($_SESSION['user_id'])) {
        if ($author_id == $_SESSION['user_id'] || $_SESSION['role'] == 1 || $_SESSION['role'] == 2) {
            $edit_buttons .= "<div class=\"btn-edit-art-container\"><a class=\"btnstyle\" href=\"edit_art.php?art={$article['id']}\">Редактировать</a>
            <a class=\"btnstyle\" href=\"/pages/go_to_draft.php?art={$article['id']}\">Снять с публикации</a>
            <a class=\"btnstyle\" href=\"/pages/delete_art.php?art={$article['id']}\">Удалить</a>";

            if ($article['status'] == 'Черновик') {
                $edit_buttons .= "<a class=\"btnstyle\" href=\"/pages/update_art.php?art={$article['id']}\">Опубликовать</a>";
            }
            $edit_buttons .= "</div>";
        }
    }

    $fields = [
        'organizational' => $article['organizational'],
        'economic' => $article['economic'],
        'marketing' => $article['marketing'],
        'phisics' => $article['phisics'],
        'technical' => $article['technical'],
        'mathematical' => $article['mathematical'],
        'normative' => $article['normative'],
        'pravo' => $article['pravo'],
        'constitutional' => $article['constitutional'],
        'socialComputer' => $article['socialComputer']
    ];

    $pdfFields = [
        'pdf1' => $article['pdf1'],
        'pdf2' => $article['pdf2'],
        'pdf3' => $article['pdf3'],
        'pdf4' => $article['pdf4'],
        'pdf5' => $article['pdf5'],
        'pdf6' => $article['pdf6'],
        'pdf7' => $article['pdf7'],
        'pdf8' => $article['pdf8'],
        'pdf9' => $article['pdf9'],
        'pdf10' => $article['pdf10']
    ];

    $fullContent = '';
    foreach ($fields as $key => $value) {
        $pdfKey = 'pdf' . (array_search($key, array_keys($fields)) + 1);
        if (!empty(trim($value)) || !empty($pdfFields[$pdfKey])) {
            if (!empty(trim($value))) {
                $fullContent .= "<p>{$value}</p>";
            }
            if (!empty($pdfFields[$pdfKey])) {
                $fullContent .= "<a style=\"color:blue;text-decoration:underline\" href=\"{$path_files}{$pdfFields[$pdfKey]}\" download>{$pdfFields[$pdfKey]}</a>";
            }
            $fullContent .= '<br>';
        }
    }

    $abstract = $article['abstract'];
    $image = $article['image'];

    $article_info = '';
    if (!empty($image)) {
        $article_info .= "<img src=\"{$path_img}{$image}\" style=\"width: 100%; height: auto; object-fit: cover;\"/>";
    }
    if (!empty($abstract)) {
        $article_info .= "<p>{$abstract}</p>";
    }

    $content = "
        <section id=\"content\">
            <div class=\"article-container\">
                <div class=\"left-column\">
                    <div class=\"article-head\">
                        <div style=\"display:flex; justify-content:space-between\">
                            <div>
                                <h2>{$art_name}</h2>
                                <p>Категория: {$categoryName}</p>
                                <p>Дата и время создания: {$created_at}</p>
                                <p>Автор: {$author_name}</p>
                                <p>Статус: {$article['status']}</p>
                                <p>Количество просмотров статьи: {$article['views']}</p>
                            </div>
                            {$edit_buttons}
                        </div>
                    </div>
                    <div class=\"article-block-full\">
                        {$fullContent}
                    </div>
                </div>
                <div class=\"article-info\">
                    {$article_info}
                </div>
            </div>
        </section>";
} else {
    $content = "<section id=\"content\"><div class=\"article-container\">Статья не найдена.</div></section>";
}

include '../templates/template.php';
?>
