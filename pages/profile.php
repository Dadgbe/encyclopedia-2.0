<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
    <style type="text/css">
        .hidden {
            display: none;
        }

        .form-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .username-form {
            background-color: #f9f9f9;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .username-form label {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            color: black;
        }
        .username-form input[type="text"] {
            width: 80%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 18px;
        }
        .username-form button {
            font-size: 18px;
            padding: 10px 20px;
            background-color: #f9f9f9;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .username-form button:hover {
            background-color: #3c5f78;
        }
        .section-button {
            background-color: #5a9fcf;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            font-size: 18px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }
        .section-button:hover {
            background-color: #3c5f78;
            color: white;
        }
        .notification-badge {
            color: white;
            padding: 2px 5px;
            font-size: 18px;
            border-radius: 50%;
            position: absolute;
            top: 10px;
            right: 0px;
            transform: translate(0%, -10px);
        }
    </style>
</head>
<body>
<?php
include_once('../templates/header.php');
require '../config/config.php';
session_start();

$userID = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username, role FROM users WHERE id = ?");
$stmt->execute([$userID]);
$user = $stmt->fetch();

$content = "<div class=\"profile-container\">
    <div class=\"profile-header\">
        <h3 class=\"user-name\">{$user['username']}</h3>
        <button class=\"section-button\" id=\"change-login\" onclick=\"toggleUsernameForm()\">Изменить логин</button>

        <div id=\"username-form-container\" class=\"hidden\">
            <div class=\"form-container\">
                <form id='username-form' class='username-form' action='../pages/update_username.php' method='post'>
                    <label for='new-username'>Новый логин:</label>
                    <input type='text' id='new-username' name='new_username' value='{$user['username']}' required>
                    <input type='hidden' name='user_id' value='$userID'>
                    <button type='submit'>Сохранить</button>
                </form>
            </div>
        </div>";

if ($user['role'] === 1 || $user['role'] === 2) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'На рецензии'");
    $countRevArticles = $stmt->fetchColumn();

    $content .= "<a href=\"users.php\" class=\"section-button\">Пользователи</a>";

    $content .= "<button class=\"section-button\" onclick=\"loadContent('review_art')\">Рецензия статей
        <span class=\"notification-badge\">$countRevArticles</span></button>";

    if ($user['role'] === 1) {
        $content .= "<a href=\"semantic_thesaurus_analysis.php\" class=\"section-button\">Семантический тезаурус - Анализ</a>";
    }
}

$stmt1 = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'Опубликовано' AND author_id = $userID");
$countPublArticles = $stmt1->fetchColumn();

$stmt2 = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'Черновик' AND author_id = $userID");
$countDraftArticles = $stmt2->fetchColumn();

$stmt3 = $pdo->query("SELECT COUNT(*) FROM articles WHERE status = 'На рецензии' AND author_id = $userID");
$countRevUserArticles = $stmt3->fetchColumn();

$content .= "<p>Ниже отображены Ваши статьи</p>
    </div>
    <div class=\"profile-content\">
        <button onclick=\"loadContent('published_articles')\" class=\"section-button\">Опубликованные<span class=\"notification-badge\">$countPublArticles</span></button>
        <button onclick=\"loadContent('draft_articles')\" class=\"section-button\">Черновики<span class=\"notification-badge\">$countDraftArticles</span></button>
        <button onclick=\"loadContent('reviews_articles')\" class=\"section-button\">На рецензии<span class=\"notification-badge\">$countRevUserArticles</span></button>
        <div id='articlesContainer' class='hidden'></div>
    </div>
    <div class=\"profile-footer\">
    </div>
</div>
<script src=\"../scripts/script.js\"></script>";

include '../templates/template.php';
?>
<script type="text/javascript">
    function loadContent(type) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../api/get_articles.php?type=' + type, true);
        xhr.onload = function() {
            if (this.status == 200) {
                var articlesContainer = document.getElementById('articlesContainer');
                articlesContainer.innerHTML = this.responseText;
                articlesContainer.classList.remove('hidden');
            }
        }
        xhr.send();
    }

    function toggleUsernameForm() {
        var formContainer = document.getElementById('username-form-container');
        if (formContainer.classList.contains('hidden')) {
            formContainer.classList.remove('hidden');
            document.getElementById('change-login').innerHTML = "Отмена";
        } else {
            formContainer.classList.add('hidden');
            document.getElementById('change-login').innerHTML = "Изменить логин";
        }
    }
</script>
</body>
</html>
