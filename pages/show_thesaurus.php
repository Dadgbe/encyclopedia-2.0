<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тезаурус</title>
    <style>
        .term-item {
            margin-bottom: 10px;
        }
        .term-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .term {
            margin-right: 10px;
        }
        .graph-img {
            max-width: 600px;
            display: block;
            margin-top: 10px;
        }
        .btn-graph {
            background-color: #5a9fcf; 
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 18px;
            margin-left: 0px;
            cursor: pointer;
            border-radius: 4px;
        }
        .btn-graph:hover {
            background-color: #3c5f78;
        }
        .btn-hide {
            background-color: #5a9fcf; 
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 18px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .btn-hide:hover {
            background-color: #3c5f78;
        }
        .term-buttons {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <?php
    require '../config/config.php';
    session_start();

    $repetitionsDir = '../thesaurus/';
    $terms = [];

    if (is_dir($repetitionsDir)) {
        $files = scandir($repetitionsDir);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == 'txt') {
                $term = pathinfo($file, PATHINFO_FILENAME);
                $term = str_replace(' повторения', '', $term);
                $terms[$term] = [];
                $filePath = $repetitionsDir . $file;
                $content = file_get_contents($filePath);
                $lines = explode("\n", $content);
                foreach ($lines as $line) {
                    if (count($terms[$term]) >= 5) break;
                    $line = trim($line);
                    if ($line) {
                        $word = explode(' - ', $line)[0];
                        $terms[$term][] = $word;
                    }
                }
            }
        }
    }

    ksort($terms);

    $content = '<div class="add-sem-th"><a href="semantic_thesaurus.php">Добавить свое определение</a></div>';
    $content .= '<div class="terms-repetitions-container">';
    $content .= '<h2 style="text-align:center">Тезаурус</h2>';
    $content .= '<ul id="terms-repetitions-list">';

    foreach ($terms as $term => $repetitions) {
        $content .= '<li class="term-item">';
        $content .= '<div class="term-content">';
        $content .= '<div class="term">';
        $content .= '<strong>' . htmlspecialchars($term, ENT_QUOTES) . '-</strong> ';
        if (empty($repetitions)) {
            $content .= 'Нет семантического ядра';
        } else {
            $content .= implode(', ', array_map(function($word) {
                return htmlspecialchars($word, ENT_QUOTES);
            }, $repetitions));
        }
        $content .= '</div>';
        $graphPath = $repetitionsDir . $term . '.png';
        if (file_exists($graphPath)) {
            $content .= '<div class="term-buttons">';
            $content .= ' <button class="btn-graph" onclick="showGraph(\'' . $term . '\', this)">Показать графический результат</button>';
            $content .= ' <button class="btn-hide" onclick="hideGraph(\'' . $term . '\', this)" style="display:none;">Скрыть графический результат</button>';
            $content .= '</div>';
        } else {
            $content .= '';
        }
        $content .= '</div>';  // закрываем term-content
        $content .= '<div id="graph-container-' . $term . '" style="display:none;"></div>';  // контейнер для графика
        $content .= '</li>';
    }

    $content .= '</ul>';
    $content .= '</div>';

    include '../templates/template.php';
    ?>

    <script>
        function showGraph(term, button) {
            const img = document.createElement('img');
            img.src = `../thesaurus/${term}.png`;
            img.alt = `Graph for ${term}`;
            img.className = 'graph-img';
            img.id = `graph-${term}`;

            const graphContainer = document.getElementById(`graph-container-${term}`);
            graphContainer.style.display = 'block';
            graphContainer.appendChild(img);

            button.style.display = 'none';
            button.nextElementSibling.style.display = 'inline-block';
        }

        function hideGraph(term, button) {
            const graphContainer = document.getElementById(`graph-container-${term}`);
            graphContainer.style.display = 'none';
            graphContainer.innerHTML = '';

            button.style.display = 'none';
            button.previousElementSibling.style.display = 'inline-block';
        }
    </script>
</body>
</html>
