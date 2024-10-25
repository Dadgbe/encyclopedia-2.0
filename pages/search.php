<?php
require '../config/config.php';
session_start();

$term = $_GET['term'] ?? '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 9;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    loadArticles($term, $offset, $limit, $sort, $filter, $pdo);
    exit;
}

function loadArticles($term, $offset, $limit, $sort, $filter, $pdo) {
    $query = "SELECT * FROM articles WHERE status = 'Опубликовано' AND (title LIKE :term1 OR organizational LIKE :term2 OR economic LIKE :term3 OR marketing LIKE :term4 OR phisics LIKE :term5 OR technical LIKE :term6 OR mathematical LIKE :term7 OR normative LIKE :term8 OR pravo LIKE :term9 OR constitutional LIKE :term10 OR socialComputer LIKE :term11)";

    $params = [
        'term1' => "%$term%",
        'term2' => "%$term%",
        'term3' => "%$term%",
        'term4' => "%$term%",
        'term5' => "%$term%",
        'term6' => "%$term%",
        'term7' => "%$term%",
        'term8' => "%$term%",
        'term9' => "%$term%",
        'term10' => "%$term%",
        'term11' => "%$term%"
    ];

    if (!empty($filter)) {
        $categoryStmt = $pdo->prepare("SELECT ID FROM categories WHERE CategoryName = :categoryname");
        $categoryStmt->execute(['categoryname' => $filter]);
        $categoryId = $categoryStmt->fetchColumn();

        if ($categoryId) {
            $query .= " AND category = :category_id";
            $params['category_id'] = $categoryId;
        }
    }

    switch ($sort) {
        case 'views':
            $query .= " ORDER BY views DESC";
            break;
        case 'date_asc':
            $query .= " ORDER BY created_at ASC";
            break;
        case 'date_desc':
        default:
            $query .= " ORDER BY created_at DESC";
            break;
    }

    $query .= " LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($query);
    $params['limit'] = $limit;
    $params['offset'] = $offset;

    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $articleCount = count($results);

    foreach ($results as $article) {
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

        echo "<div class=\"article-block\">";
        if (!empty($artimg)) {
            echo "<img src=\"$path_img$artimg\"/>";
        }

        echo "<h3>{$article['title']}</h3>
              <div>{$previewContent}...</div>
              <a class=\"read-button\" href=\"../pages/article.php?article={$article['id']}\">Читать</a>
              </div>";
    }

    echo '<script>var loadedArticles = ' . $articleCount . ';</script>';
}

$content = '';
$categoriesStmt = $pdo->query("SELECT * FROM categories");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
$content .= '<div class="content-container">';

if (!empty($term)) {
    ob_start();
    loadArticles($term, $offset, $limit, $sort, $filter, $pdo);
    $initialArticles = ob_get_clean();

    $content .= '<div class="rows-container" id="articles-container">';
    $content .= $initialArticles;

    if (count(explode('<div class="article-block">', $initialArticles)) - 1 < $limit) {
        $content .= '<div class="load-more-container" style="display:none;"><button id="load-more" class="load-more-btn">Показать еще</button></div>';
    } else {
        $content .= '<div class="load-more-container"><button id="load-more" class="load-more-btn">Показать еще</button></div>';
    }
    $content .= '</div>';
} else {
    $content .= "<div class=\"rows-container\">Пожалуйста, введите запрос для поиска.</div>";
}

$content .= '
    <div class="search-form-container">
        <form method="GET" class="search-form">
            <input type="text" id="searchInput" class="search-input" name="term" value="' . htmlspecialchars($term, ENT_QUOTES) . '"/>
            <label for="sort">Сортировать по:</label>
            <select name="sort" id="sort" class="form-select">
                <option value="views"' . ($sort == 'views' ? ' selected' : '') . '> просмотрам</option>
                <option value="date_desc"' . ($sort == 'date_desc' ? ' selected' : '') . '>от новых к старым</option>
                <option value="date_asc"' . ($sort == 'date_asc' ? ' selected' : '') . '>от старых к новым</option>
            </select>
            <label for="filter">Категория:</label>
            <select name="filter" id="filter">
                <option value=""' . ($filter == '' ? ' selected' : '') . '>Все</option>';

foreach ($categories as $category) {
    $selected = ($filter == $category['CategoryName']) ? ' selected' : '';
    $content .= '<option value="' . htmlspecialchars($category['CategoryName'], ENT_QUOTES) . '"' . $selected . '>' . htmlspecialchars($category['CategoryName'], ENT_QUOTES) . '</option>';
}

$content .= '
            </select>
            <button type="submit">Применить</button>
        </form>
    </div>
</div>';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск</title>
    <link rel="stylesheet" href="/assets/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script
</head>
<body>
    <?php include_once('../templates/header.php'); ?>
    <main>
        <section id="content">
            <?php echo $content; ?>
        </section>
    </main>
    <?php include_once('../templates/footer.php'); ?>
    <script src="../scripts/script.js"></script>
    <script src="../scripts/scroll.js"></script>
    <script src="../scripts/searchHistory.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let offset = <?= $limit ?>;
        const limit = <?= $limit ?>;
        const sort = '<?= $sort ?>';
        const filter = '<?= $filter ?>';
        const term = '<?= htmlspecialchars($term, ENT_QUOTES) ?>';
        const loadMoreButton = document.getElementById('load-more');

        loadMoreButton.addEventListener('click', function() {
            fetch(`../pages/search.php?ajax=1&offset=${offset}&sort=${sort}&filter=${filter}&term=${term}`)
                .then(response => response.text())
                .then(data => {
                    const container = document.getElementById('articles-container');
                    const loadMoreContainer = document.querySelector('.load-more-container');
                    container.insertAdjacentHTML('beforeend', data);
                    offset += limit;
                    container.appendChild(loadMoreContainer);

                    const loadedArticles = document.querySelectorAll('.article-block').length;
                    if (loadedArticles < offset) {
                        loadMoreContainer.style.display = 'none';
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        });
    });
    </script>
</body>
</html>
