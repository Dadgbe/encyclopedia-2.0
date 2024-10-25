<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Семантический тезаурус</title>
</head>
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $term = trim($_POST['term']);
    $definition = trim($_POST['definition']);
    $filename = preg_replace('/\s+/', '_', $term) . ".txt";
    $filePath = "../thesaurus/Определения/" . $filename;
    $formattedDefinition = $term . " - это " . $definition;
    file_put_contents($filePath, $formattedDefinition . PHP_EOL, FILE_APPEND);

    $_SESSION['notification'] = "Ваше определение на термин \"" . htmlspecialchars($term) . "\" сохранено:<br><br>" . htmlspecialchars($formattedDefinition);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$notification = "";
if (isset($_SESSION['notification'])) {
    $notificationMessage = $_SESSION['notification'];
    $notificationScript = "<script>document.getElementById('notification').style.display = 'flex'; document.getElementById('notification-content').innerHTML = '" . addslashes($notificationMessage) . "';</script>";
    unset($_SESSION['notification']);
}

$header = "<div class=\"input-terms-container\"><h2 style=\"text-align:center\">Введите термин и его определение</h2>";

$form = "<form action='' method='post' class=\"terms-form\">
            <label for='term'>Термин:</label>
            <input type='text' id='term' name='term' required>
            <br>
            <label for='definition'>Определение:</label>
            <textarea id='definition' name='definition' required></textarea>
            <br>
            <button type='submit' class='btnstyle'>Сохранить</button>
         </form>";

$content = $header . $form . $notification . "</div>";
?>

<div id="notification" class="notification-overlay" style="display: none;">
    <div class="notification-box">
        <p id="notification-content">Определение сохранено</p>
        <button onclick="closeNotification()">ОК</button>
    </div>
</div>

<script>
    function closeNotification() {
        document.getElementById('notification').style.display = 'none';
    }
</script>

<?= $notificationScript ?? '' ?>

<?php
include '../templates/template.php';
?>
