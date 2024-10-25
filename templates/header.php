<header>
    <div style="height:350px;">
        <div class="header-container">
            <div class="auth-reg-container">
                <div><a href="/pages/show_thesaurus.php">Семантический тезаурус</a></div>
                <div>
                <?php
                session_start();
                if (isset($_SESSION['user_id'])) {
                    $username = $_SESSION['user_name'];
                    echo '<a href="../pages/profile.php">Здравствуйте, '.$username.'</a>';
                    echo '<a href="../pages/new_art.php">Добавить статью</a>';
                    echo '<a href="../pages/exit.php">Выйти</a>';
                } else {
                    echo '<a href="../pages/auth.php">Войти</a>
                          <a href="../pages/reg.php">Зарегистрироваться</a>';
                }
                ?>
                </div>
            </div>

            <div class="navigation">
                <nav id="navigation">
                    <ul>
                        <li><img src="/public/img/search.png" onclick="toggleSearchForm()"></li>
                    </ul>
                </nav>
            </div>
        </div>

        <h1><a style="color: white;" href="../public/index.php">Цифровая энциклопедия</a></h1>
        <form id="searchForm" method="GET" action="../pages/search.php" style="display:none;">
            <input type="text" id="searchInput" name="term" placeholder="Поиск статей..." oninput="showSuggestions(this.value)">
            <button type="submit">Поиск</button>
            <div id="suggestions" class="suggestions-box"></div>
        </form>
        <a href="/pages/users_guide.php" class="user-guide-link">Руководство пользователя</a>
    </div>
    <div class="visit-history" id="historyContainer"></div>
</header>

<?php
require '../config/config.php';

$popularSearchesStmt = $pdo->query("SELECT title FROM articles ORDER BY views DESC LIMIT 5");
$popularSearches = $popularSearchesStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<script>
    const popularSearches = <?php echo json_encode($popularSearches); ?>;
</script>
