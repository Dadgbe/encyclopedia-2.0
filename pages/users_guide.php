<?php
session_start();

$header = "<h1>Руководство пользователя</h1>";

$sections = [
    [
        "title" => "Добро пожаловать",
        "content" => "Здесь вы найдете информацию о том, как пользоваться нашим сайтом. Мы постарались сделать его удобным и интуитивно понятным."
    ],
    [
        "title" => "Регистрация и Вход",
        "content" => "Для того чтобы воспользоваться всеми возможностями сайта, пожалуйста, зарегистрируйтесь или войдите в свою учетную запись.",
        "image" => "../public/guide/1.png",
        "image_desc" => "Для регистрации нажмите на кнопку Зарегистрироваться"
    ],
    [
        "title" => "Процесс регистрации",
        "content" => "Для регистрации введите Ваше имя пользователя. Далее, введите Ваш пароль и подтвердите его в следующем поле, после чего нажмите на кнопку Зарегистрироваться. Процесс регистрации завершен.",
        "image" => "../public/guide/2.png",
        "image_desc" => "Пример формы регистрации на сайте"
    ],
    [
        "title" => "Процесс авторизации",
        "content" => "После успешной регистрации, Вас сразу же перенаправят на странциу авторизации. Введите Ваше имя пользователя, которое вы использовали при регистрации и Ваш пароль, после нажмите на кнопку Войти. После успешной авторизации на Цифровой энциклопедии, Вас перенаправят на главную страницу. Процесс авторизации завершен.",
        "image" => "../public/guide/3.png",
        "image_desc" => "Пример формы авторизации на сайте"
    ],
    [
        "title" => "Добавление статьи",
        "content" => "После входа в систему вы сможете добавлять свои статьи. Для этого перейдите на страницу Добавить статью и следуйте инструкциям.",
        "image" => "../public/guide/4.png",
        "image_desc" => "Добавление статьи"
    ],
    [
        "title" => "Добавление статьи",
        "content" => "Для начала укажите заголовок статьи и выберите необходимую категорию в выпадающем списке",
        "image" => "../public/guide/5.png",
        "image_desc" => "Заголовок и категория статьи"
    ],
    [
        "title" => "10 сторон статьи",
        "content" => "Далее заполните все 10 сторон статьи. По возможности, так же прикрепите к каждой стороне pdf файл с подтверждением",
        "image" => "../public/guide/6.png",
        "image_desc" => "Заполните 10 сторон статьи"
    ],
    [
        "title" => "Краткая информация для картинки",
        "content" => "В завершение укажите краткую информацию, которая будет выводится под изображением статьи.",
        "image" => "../public/guide/7.png",
        "image_desc" => "Краткая информация"
    ],
    [
        "title" => "Семантический тезаурус",
        "content" => "Цифровой семантический тезаурус предназначен для формирования унифицированных структур терминов и определений в виде семантических ядер и добавлен с целью использования этих семантических ядер для обеспечения минимального разброса в понимании смысла этих терминов и определений.
        Программа дает возможность как обоснования, так и однозначного понимания управленческих решений в науке, производстве, образовании и других отраслях на основе единого понимания смысла терминов и определений. Для того, чтобы воспользоваться семантическим тезаурусом, перейдите по ссылке Семантический тезаурус в шапке сайта",
        "image" => "../public/guide/8.png",
        "image_desc" => "Семантический тезаурус"
    ],
    [
        "title" => "Семантический тезаурус",
        "content" => "Введите в поле термин, а так же его определение, после чего нажмите на кнопку Сохранить.",
        "image" => "../public/guide/9.png",
        "image_desc" => "Семантический тезаурус"
    ],
];

$content = "<style>
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
    color: #333;
}

.users-guide-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.users-guide-container h1, .users-guide-container h2 {
    text-align: center;
    color: #333;
}

.users-guide-container section {
    margin-bottom: 20px;
}

.users-guide-container section h2 {
    background-color: #548dab;
    color: white;
    padding: 10px;
    border-radius: 5px;
}

.users-guide-container section p {
    padding: 10px;
    background-color: #f1f1f1;
    border-left: 5px solid #888;
    margin: 10px 0;
}

.image-container {
    text-align: center;
    margin: 20px 0;
}

.image-container img {
    max-width: 100%;
    height: auto;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    background-color: #fff;
}

.image-container p {
    font-size: 0.9em;
    color: #666;
    margin-top: 5px;
}
</style>";

$content .= "<div class='users-guide-container'>";
foreach ($sections as $section) {
    $content .= "<section><h2>{$section['title']}</h2><p>{$section['content']}</p>";
    if (isset($section['image'])) {
        $content .= "<div class='image-container'><img src='{$section['image']}' alt='{$section['title']}'><p>{$section['image_desc']}</p></div>";
    }
    $content .= "</section>";
}
$content .= "</div>";

include '../templates/template.php';
?>
