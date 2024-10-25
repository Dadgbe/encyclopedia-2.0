<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Информация для авторов</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        h3 {
            color: #666;
        }
        h4 {
            font-size: 20px;
        }
        p {
            margin: 10px 0;
        }
        .example {
            background: #e9ecef;
            padding: 10px;
            border-left: 5px solid #007bff;
            margin: 20px 0;
        }
        .formatting-buttons {
            margin: 10px 0;
        }
        .formatting-buttons button {
            margin-right: 5px;
            padding: 5px 10px;
            border: none;
            background: #007bff;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }
        .formatting-buttons button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <?php
    include_once('../templates/header.php');
    require '../config/config.php';
    session_start();
    ?>
    <div class="container">
        <h2>Информация для авторов</h2>
        <p>Создание/редактирование статьи состоит из следующих полей: заголовок, категория, изображение, краткая информация и 10 сторон рассмотрения обозреваемого вопроса статьи (объекта). Это нужно для наиболее безопасного и целостного рассмотрения объекта, однако заполнение всех полей не является обязательным.</p>
        <p>Каждое заполняемое поле (10 сторон и краткая информация) имеет кнопки форматирования текста (Ж – полужирный, К – курсив, Ч – подчеркивание и т. д.). Можно создать перечисление, а также пометить текст гиперссылкой.</p>
        <div class="formatting-buttons">
            <button><b>Ж</b></button>
            <button><i>К</i></button>
            <button><u>Ч</u></button>
            <button>🔗</button>
        </div>
        <p>Для каждой из сторон присутствует возможность загрузить файл pdf формата. Если Вы имеете «подтверждение» излагаемой информации или рассмотрение объекта с той или иной стороны в файле, то Вы всегда можете загрузить этот файл, а в дальнейшем любой пользователь сможет его скачать для ознакомления прямо из статьи.</p>
        <p>В поле «Краткая информация» Вы можете указать те факты, которые могут быть вынесены из общей информации об объекте и при этом будут важны для ознакомления (например, дата, места, язык и т. д.).</p>
        <p>Итак, после проверки всех полей, Вы можете нажать на «Опубликовать» или «Сохранить изменения» (при редактировании статьи) и Ваша статья будет отправлена на рецензирование администратору или преподавателю. Отслеживайте статус статьи у себя в профиле!</p>

        <h3>Пример рассмотрения объекта с 10 сторон</h3>
        <div class="example">
            <h4>Объект – Школа</h4>
            <p><b>Организационная сторона:</b> Школа управляется администрацией, включающей директора, завучей и административный персонал. Организационная структура включает также преподавательский состав, работников хозяйственной части и вспомогательный персонал. Каждый отдел и сотрудник имеет четко определенные обязанности и задачи, что обеспечивает слаженное функционирование учебного заведения.</p>
            <p><b>Экономическая сторона:</b> Финансирование школы может быть как государственным, так и частным. Экономическая сторона включает управление бюджетом, который распределяется на зарплаты учителям и персоналу, закупку учебных материалов, оборудование, ремонт и модернизацию помещений. Эффективное управление бюджетом помогает поддерживать высокое качество образования.</p>
            <p><b>Маркетинговая сторона:</b> Школа продвигает свои образовательные программы и достижения через различные каналы. Это может быть реклама в местных СМИ, участие в образовательных выставках, проведение дней открытых дверей. Хорошая репутация, позитивные отзывы родителей и учеников, внешний и внутренний вид здания школы также играют важную роль в маркетинговой стратегии.</p>
            <p><b>Физическая сторона:</b> Школа включает учебные корпуса, спортивные залы, библиотеки, лаборатории и другие помещения. Физическая инфраструктура должна быть безопасной, удобной и соответствовать санитарным и образовательным нормам.</p>
            <p><b>Техническая сторона:</b> Техническое оснащение школы включает компьютеры, интерактивные доски, проекторы и другие образовательные технологии. Важно обеспечить бесперебойное функционирование технического оборудования и постоянное обновление программного обеспечения. Также сюда относится работа локальных сетей и систем безопасности.</p>
            <p><b>Математическая сторона:</b> Математический аспект включает в себя планирование расписания занятий, распределение учебной нагрузки, анализ успеваемости учеников и эффективность использования ресурсов. Математические модели помогают оптимизировать процессы управления и принятия решений.</p>
            <p><b>Нормативная сторона:</b> Школа должна соблюдать образовательные стандарты и нормы, установленные государственными органами. Это включает в себя выполнение учебных планов, проведение аттестаций и экзаменов, обеспечение условий для получения качественного образования. Нормативная сторона также охватывает требования к квалификации учителей и условиям труда.</p>
            <p><b>Правовая сторона:</b> Правовая сторона включает соблюдение всех законов и правовых актов, регулирующих деятельность образовательных учреждений. Это касается трудового права, защиты прав детей, соблюдения санитарных и пожарных норм. Школа обязана вести документацию в соответствии с законодательством и защищать личные данные учащихся и сотрудников.</p>
            <p><b>Конституционная сторона:</b> Конституционная сторона подразумевает обеспечение прав на образование, закрепленных в конституции. Школа должна гарантировать доступность образования для всех детей, вне зависимости от их социального положения, расы, пола и религиозных убеждений. Обеспечение равных возможностей для всех учеников — основополагающий принцип конституционного права.</p>
            <p><b>Социально-компьютерная сторона:</b> Интеграция компьютерных технологий в образовательный процесс способствует улучшению качества обучения. Школа использует электронные дневники, онлайн-платформы для домашних заданий, системы дистанционного обучения. Важно обеспечить ученикам и учителям доступ к современным цифровым ресурсам и обучить их использованию новых технологий. Социальные аспекты включают взаимодействие школы с родителями через электронные системы и создание благоприятной учебной среды.</p>
        </div>
    </div>
    <?php
    $content = ob_get_clean(); // Захват вывода буфера
    include '../templates/template.php';
    ?>
</body>
</html>