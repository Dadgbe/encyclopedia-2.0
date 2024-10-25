<?php
require '../config/config.php';
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'analyze_all') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    function logError($message) {
        error_log($message, 3, '../file.log');
    }

    function respondWithError($message) {
        logError($message);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['status' => 'error', 'message' => $message], JSON_UNESCAPED_UNICODE);
        exit();
    }

    try {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            respondWithError('Ошибка декодирования JSON: ' . json_last_error_msg());
        }

        if (!isset($input['terms']) || !is_array($input['terms'])) {
            respondWithError('Некорректный запрос');
        }

        $terms = $input['terms'];
        $thesaurusDir = '../thesaurus/Определения/';
        $pythonScript = '../thesaurus/semantic_thesaurus.py';
        $success = true;

        foreach ($terms as $term) {
            $filename = $thesaurusDir . $term . '.txt';

            if (!file_exists($filename)) {
                respondWithError('Файл не найден: ' . $filename);
            }

            exec("/usr/bin/python3.10 $pythonScript $thesaurusDir $term 2>&1", $output, $return_var);

            if ($return_var !== 0) {
                $success = false;
                logError('Ошибка выполнения Python скрипта для термина: ' . $term . '. Выходной код: ' . $return_var . '. Вывод: ' . implode("\n", $output));
            }
        }

        if ($success) {
            $message = 'Все термины успешно проанализированы.';
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode(['status' => 'success', 'message' => $message], JSON_UNESCAPED_UNICODE);
        } else {
            respondWithError('Произошла ошибка при анализе некоторых терминов. Проверьте журнал ошибок для подробной информации.');
        }
    } catch (Exception $e) {
        respondWithError('Исключение: ' . $e->getMessage());
    }

    exit();
}
?>
