<?php
require '../config/config.php';
session_start();

$thesaurusDir = '../thesaurus/Определения/';
$terms = [];

if (is_dir($thesaurusDir)) {
    $files = scandir($thesaurusDir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) == 'txt') {
            $terms[] = pathinfo($file, PATHINFO_FILENAME);
        }
    }
}

$termsListClass = count($terms) > 10 ? 'scrollable-terms' : '';

$content = "<div class='container'>
    <div class='header'>
        <h1>Семантический тезаурус - Анализ</h1>
    </div>
    <div class='analysis-content'>
        <p>Добро пожаловать на страницу анализа семантического тезауруса. Здесь вы можете проводить анализ и исследование семантических связей.</p>
        <div class='thesaurus-section' id='thesaurus-section'>
            <ul id='terms-list' class='$termsListClass'>";

foreach ($terms as $term) {
    $content .= "<li class='thesaurus-term term-item'>" . htmlspecialchars($term, ENT_QUOTES) . "</li>";
}

$content .= "</ul>
            <button id='analyze-all' class='section-button'>Анализировать все термины</button>
            <div id='definition-container' class='definition-container'></div>
        </div>
    </div>
</div>";

include '../templates/template.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const termsList = document.querySelectorAll('.term-item');
    const definitionContainer = document.getElementById('definition-container');
    const thesaurusSection = document.getElementById('thesaurus-section');
    let originalContent = thesaurusSection.innerHTML;

    const analyzeAllButton = document.getElementById('analyze-all');

    analyzeAllButton.addEventListener('click', function() {
        const terms = Array.from(termsList).map(item => item.textContent);

        definitionContainer.innerHTML = '<p>Анализ терминов начался, пожалуйста, подождите...</p>';

        fetch('../pages/analyze_terms.php?action=analyze_all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ terms: terms })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                definitionContainer.innerHTML = `<p>${data.message}</p>`;
            } else {
                definitionContainer.innerHTML = `<p>Ошибка: ${data.message}</p>`;
            }
        })
        .catch(error => {
            definitionContainer.innerHTML = `<p>Ошибка: ${error.message}</p>`;
        });
    });
});
</script>

<style type="text/css">
.container {
    margin: 20px;
}
.header {
    text-align: center;
    margin-bottom: 20px;
}
.header h1 {
    font-size: 32px;
    color: #333;
}
.analysis-content {
    background-color: #f9f9f9;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.analysis-content p {
    font-size: 18px;
    color: #555;
}
.thesaurus-section {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #fff;
    margin-top: 20px;
}
#terms-list.scrollable-terms {
    max-height: 400px; /* Adjust as needed */
    overflow-y: auto;
}
.thesaurus-term {
    font-size: 18px;
    color: #333;
    text-decoration: none;
    display: block;
    margin: 5px 0;
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
.definition-container {
    margin-top: 20px;
    padding: 10px;
    background-color: #f1f1f1;
    border: 1px solid #ccc;
    min-height: 50px;
}
</style>
