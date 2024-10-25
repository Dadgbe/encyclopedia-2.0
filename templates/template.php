<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Главная страница</title>
    <link rel="stylesheet" href="/assets/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <?php include_once('header.php'); ?>
    <main>
        <section id="content">
            <?php echo $content; ?>
        </section>
    </main>

    <?php include_once('footer.php'); ?>
    <script src="../scripts/script.js"></script>
    <script src="../scripts/scroll.js"></script>
    <script src="../scripts/searchHistory.js"></script>
</body>
</html>
