<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из тела запроса
    $data = file_get_contents('php://input');

    // Декодируем JSON-данные в массив
    $results = json_decode($data, true);

    // Получаем значение test из отправленных данных
    $test = $results['test'];

    // Создаем имя файла на основе полученных данных
    $username = str_replace(' ', '_', strtolower($results['fullName']));

    // Проверяем, существует ли файл с таким именем
    $attempt = 1;
    $filename = "results/{$test}_{$username}.json";
    while (file_exists($filename)) {
        $attempt++;
        $filename = "results/{$test}_{$username}_attempt={$attempt}.json";
    }

    // Записываем данные в файл
    file_put_contents($filename, $data);

    // Отправляем ответ клиенту
    http_response_code(200);
    echo json_encode(array("message" => "Results saved successfully!"));
} else {
    // Если запрос не является POST-запросом, возвращаем ошибку
    http_response_code(405);
    echo json_encode(array("message" => "Method Not Allowed"));
}
?>
