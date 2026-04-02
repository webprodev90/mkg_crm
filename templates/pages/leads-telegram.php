<?
$token = "eyJhbGciOiJdfghSUzI3NdfghiIsImtpZC34523sfgh565IjI2Y";

$data = array(
    "date" => "05.11.2025",
    "username" => "@oleg_zakup",
    "fio" => "Олег|Telegram Ads",
    "tgID" => "7389850892",
    "start" => "test0003",
    "otvet1" => "1, 2, 3, 4",
    "otvet2" => "500000",
    "otvet2_1" => "1500000",
    "otvet2_2" => "2500000",
    "otvet3" => "1",
    "otvet3.1" => "12",
    "otvet4" => "1, 3",
    "otvet5" => "1, 2",
    "otvet5.1" => "прописан 1",
    "otvet6" => "каникулы",
    "otvet7" => "питер",
    "otvet8" => "иван",
    "otvet9" => "79297722670",
	"otvet10" => "нет"
);

// Инициализация cURL
$ch = curl_init('https://crm.mkggroup.ru/api/rest.php');

// Установка параметров cURL
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);

// Включение подробного лога для отладки
ob_start();
$out = fopen('php://output', 'w');
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_STDERR, $out);

// Выполнение запроса
$res = curl_exec($ch);

// Получение информации о запросе для отладки
fclose($out);
$debug = ob_get_clean();

// Проверка на ошибки cURL
if (curl_errno($ch)) {
    echo "Ошибка cURL: " . curl_error($ch) . "\n";
    echo "Код ошибки: " . curl_errno($ch) . "\n";
    echo "Отладочная информация:\n" . $debug;
} else {
    // Получение HTTP-статуса
    $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "HTTP-статус: " . $httpStatusCode . "\n";

    // Проверка HTTP-статуса
    if ($httpStatusCode >= 200 && $httpStatusCode < 300) {
        echo "Запрос выполнен успешно!\n";
        echo "Ответ сервера: " . $res . "\n";
    } else {
        echo "Ошибка сервера. Статус: " . $httpStatusCode . "\n";
        echo "Ответ сервера: " . $res . "\n";
    }
}

// Закрытие cURL сессии
curl_close($ch);


?>