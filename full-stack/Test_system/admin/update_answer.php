<?php
date_default_timezone_set('America/Los_Angeles');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question']) && isset($_POST['answer']) && isset($_POST['filename'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $filename = $_POST['filename'];

    $fileData = json_decode(file_get_contents($filename), true);

    foreach ($fileData['answers'] as &$fileAnswer) {
        if ($fileAnswer['question'] === $question) {
            $fileAnswer['correct'] = false; // Устанавливаем значение false, так как ответ был неправильным
            break;
        }
    }

    file_put_contents($filename, json_encode($fileData, JSON_PRETTY_PRINT)); // Обновляем файл с результатами
    exit; // Завершаем выполнение PHP после обновления файла
}
?>
<?php
