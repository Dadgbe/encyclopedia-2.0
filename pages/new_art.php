<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/style.css">
    <title>Добавить новую статью</title>
    <script src="../scripts/script.js"></script>
    <script src="../scripts/editor.js" defer></script>
    <style>
        .editor a {
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php
session_start();
require '../config/config.php';
include_once('../templates/header.php');

$categoriesStmt = $pdo->query("SELECT * FROM categories");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

$notificationScript = "";
if (isset($_SESSION['message'])) {
    $notificationMessage = addslashes($_SESSION['message']);
    $notificationScript = "<script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('notification').style.display = 'flex';
            document.getElementById('notification-content').innerHTML = '{$notificationMessage}';
        });
    </script>";
    unset($_SESSION['message']);
}

$fields = [
    "organizational" => "Организационная сторона",
    "economic" => "Экономическая сторона",
    "marketing" => "Маркетинговая сторона",
    "phisics" => "Физическая сторона",
    "technical" => "Техническая сторона",
    "mathematical" => "Математическая сторона",
    "normative" => "Нормативная сторона",
    "pravo" => "Правовая сторона",
    "constitutional" => "Конституционная сторона",
    "socialComputer" => "Социально-компьютерная сторона"
];

$content = "<div class=\"add-article-container\">
    <h2>Добавить новую статью</h2>
    <a style=\"color:blue;text-decoration:underline\" href=\"for_authors.php\" >Памятка автору</a>
    <form id=\"new-article-form\" action=\"submit_art.php\" method=\"post\" class=\"add-article-form\" enctype=\"multipart/form-data\">
        <div class=\"form-group\">

            <br>
            <label for=\"title\">Заголовок:</label>
            <input type=\"text\" id=\"title\" name=\"title\" required>
        </div>
        <div class=\"form-group\">
            <label for=\"category\">Категория:</label>
            <select name=\"category\" required>";
foreach ($categories as $category) {
    $content .= '<option value="' . htmlspecialchars($category['CategoryName'], ENT_QUOTES) . '">' . htmlspecialchars($category['CategoryName'], ENT_QUOTES) . '</option>';
}
$content .= "
            </select>
        </div>
        <div class=\"form-group\">
            <label for=\"image\">Изображение:</label>
            <input type=\"file\" id=\"image\" name=\"image\" accept=\"image/*\">
            <p id=\"image-error\" style=\"color: red; display: none;\">Пожалуйста, загрузите файл изображения.</p>
        </div>";

$i = 1;
foreach ($fields as $field => $label) {
    $content .= "
        <div class=\"form-group\">
            <div class=\"field-header\">
                <label for=\"editor{$i}\">{$label}:</label>
                <span class=\"toggle-icon\" onclick=\"toggleField({$i})\" id=\"toggle-icon-{$i}\">&#9660;</span>
            </div>
            <div id=\"editor-container{$i}\" class=\"editor-container\" style=\"display:none;\">
                <div class=\"editor-toolbar\">
                    <button type=\"button\" id=\"bold-btn-{$i}\" onclick=\"formatText('bold', 'editor{$i}')\"><b>Ж</b></button>
                    <button type=\"button\" id=\"italic-btn-{$i}\" onclick=\"formatText('italic', 'editor{$i}')\"><i>К</i></button>
                    <button type=\"button\" id=\"underline-btn-{$i}\" onclick=\"formatText('underline', 'editor{$i}')\"><u>Ч</u></button>
                    <button type=\"button\" id=\"ul-btn-{$i}\" onclick=\"formatText('insertUnorderedList', 'editor{$i}')\">• ——<br>• ——</button>
                    <button type=\"button\" id=\"ol-btn-{$i}\" onclick=\"formatText('insertOrderedList', 'editor{$i}')\">1. ——<br>2. ——</button>
                    <button type=\"button\" id=\"link-btn-{$i}\" onclick=\"insertLink('editor{$i}')\">🔗</button>
                </div>
                <div id=\"editor{$i}\" contenteditable=\"true\" class=\"editor\"></div>
                <textarea name=\"textarea{$i}\" id=\"textarea{$i}\" style=\"display: none;\"></textarea>
                <div class=\"form-group\">
                    <label for=\"pdf{$i}\">PDF файл:</label>
                    <input type=\"file\" id=\"pdf{$i}\" name=\"pdf{$i}\" accept=\"application/pdf\">
                </div>
            </div>
        </div>";
    $i++;
}

$content .= "
        <div class=\"form-group\">
            <label for=\"brief_info\">Краткая информация:</label>
            <div class=\"editor-toolbar\">
                <button type=\"button\" id=\"bold-btn-brief\" onclick=\"formatText('bold', 'brief_info')\"><b>Ж</b></button>
                <button type=\"button\" id=\"italic-btn-brief\" onclick=\"formatText('italic', 'brief_info')\"><i>К</i></button>
                <button type=\"button\" id=\"underline-btn-brief\" onclick=\"formatText('underline', 'brief_info')\"><u>Ч</u></button>
                <button type=\"button\" id=\"ul-btn-brief\" onclick=\"formatText('insertUnorderedList', 'brief_info')\">• ——<br>• ——</button>
                <button type=\"button\" id=\"ol-btn-brief\" onclick=\"formatText('insertOrderedList', 'brief_info')\">1. ——<br>2. ——</button>
                <button type=\"button\" id=\"link-btn-brief\" onclick=\"insertLink('brief_info')\">🔗</button>
            </div>
            <div id=\"brief_info\" contenteditable=\"true\" class=\"editor\"></div>
            <textarea name=\"brief_info\" id=\"textarea_brief_info\" style=\"display: none;\"></textarea>
        </div>
        <button class=\"add-article-btn\" type=\"submit\" onclick=\"updateTextareas()\">Опубликовать</button>
    </form>
</div>";
?>

<div id="notification" class="notification-overlay" style="display: none;">
    <div class="notification-box">
        <p id="notification-content">Изменения успешно сохранены</p>
        <button onclick="closeNotification()">ОК</button>
    </div>
</div>

<script>
    function closeNotification() {
        window.location.href = '../public/index.php';
    }

    function formatText(command, index) {
        const editor = document.getElementById(index);
        editor.focus();
        document.execCommand(command, false, null);
    }

    function updateTextareas() {
        for (let i = 1; i <= 10; i++) {
            document.getElementById('textarea' + i).value = document.getElementById('editor' + i).innerHTML;
        }
        document.getElementById('textarea_brief_info').value = document.getElementById('brief_info').innerHTML;
    }

    function toggleField(index) {
        const editorContainer = document.getElementById('editor-container' + index);
        const toggleIcon = document.getElementById('toggle-icon-' + index);
        if (editorContainer.style.display === 'none') {
            editorContainer.style.display = 'block';
            toggleIcon.innerHTML = '&#9650;';
        } else {
            editorContainer.style.display = 'none';
            toggleIcon.innerHTML = '&#9660;';
        }
    }

    function insertLink(editorId) {
        const url = prompt("Введите URL:");
        if (url) {
            document.execCommand('createLink', false, url);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const editors = document.querySelectorAll('.editor');
        editors.forEach(editor => {
            let text = editor.innerText;
            editor.innerHTML = '';
            editor.focus();
            document.execCommand('insertHTML', false, text);
        });

        document.getElementById('title').focus();
    });

    document.getElementById('new-article-form').addEventListener('submit', function(event) {
        const imageInput = document.getElementById('image');
        const imageError = document.getElementById('image-error');
        const file = imageInput.files[0];

        if (file && !file.type.startsWith('image/')) {
            imageError.style.display = 'block';
            event.preventDefault();
        } else {
            imageError.style.display = 'none';
        }
    });
</script>

<?= $notificationScript ?? '' ?>

<?php
include_once('../templates/template.php');
?>
</body>
</html>
