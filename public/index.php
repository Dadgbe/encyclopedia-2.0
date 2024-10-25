<?php
require '../config/config.php';
session_start();

$page = isset($_GET['article']) ? $_GET['article'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 9;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    loadArticles($offset, $limit, $sort, $filter, $pdo);
    exit;
}

function loadArticles($offset, $limit, $sort, $filter, $pdo) {
    $query = "SELECT * FROM articles WHERE status = 'Опубликовано'";
    $countQuery = "SELECT COUNT(*) FROM articles WHERE status = 'Опубликовано'";

    if (!empty($filter)) {
        $categoryStmt = $pdo->prepare("SELECT ID FROM categories WHERE CategoryName = :categoryname");
        $categoryStmt->execute(['categoryname' => $filter]);
        $categoryId = $categoryStmt->fetchColumn();

        if ($categoryId) {
            $query .= " AND category = :category_id";
            $countQuery .= " AND category = :category_id";
        } else {
            $filter = '';
        }
    }

    // Get the total count of articles
    $countStmt = $pdo->prepare($countQuery);
    if (!empty($filter) && isset($categoryId)) {
        $countStmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
    }
    $countStmt->execute();
    $totalArticles = $countStmt->fetchColumn();

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

    if (!empty($filter) && isset($categoryId)) {
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
    }

    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $articles = $stmt->fetchAll();

    foreach ($articles as $article) {
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

    echo '<script>var totalArticles = ' . $totalArticles . ';</script>';
    echo '<script>var loadedArticles = ' . count($articles) . ';</script>';
}

$content = '';
$categoriesStmt = $pdo->query("SELECT * FROM categories");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

if ($page === null) {
    ob_start();
    loadArticles($offset, $limit, $sort, $filter, $pdo);
    $initialArticles = ob_get_clean();

    $content .= '<div class="content-container">';
    $content .= '<div class="rows-container" id="articles-container">';
    $content .= $initialArticles;
    $content .= '<div class="load-more-container"><button id="load-more" class="load-more-btn">Показать еще</button></div>';
    $content .= '</div>';

    $content .= '
        <div class="search-form-container sticky-form">
            <form method="GET" class="search-form">
                <label for="sort">Сортировать по:</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="views"' . ($sort == 'views' ? ' selected' : '') . '> просмотрам</option>
                    <option value="date_desc"' . ($sort == 'date_desc' ? ' selected' : '') . '>от новых к старым</option>
                    <option value="date_asc"' . ($sort == 'date_asc' ? ' selected' : '') . '>от старых к новым</option>
                </select><br>
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
} else {
    header("Location: ../public/index.php");
    exit();
}

include '../templates/template.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let offset = <?= $limit ?>;
    const limit = <?= $limit ?>;
    const sort = '<?= $sort ?>';
    const filter = '<?= $filter ?>';
    const loadMoreButton = document.getElementById('load-more');

    if (typeof totalArticles !== 'undefined' && typeof loadedArticles !== 'undefined' && totalArticles <= loadedArticles) {
        document.querySelector('.load-more-container').style.display = 'none';
    }

    loadMoreButton.addEventListener('click', function() {
        fetch(`../public/index.php?ajax=1&offset=${offset}&sort=${sort}&filter=${filter}`)
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
