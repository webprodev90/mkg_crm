<?php
require_once dirname(__FILE__) . '/access7.php';

// URL для получения всех воронок (pipelines)
$url = "https://$subdomain.amocrm.ru/api/v4/leads/pipelines";

// Инициализация cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token
]);

// Выполняем запрос
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true); // преобразуем JSON в массив
    echo '<pre>';
    print_r($data); // смотрим структуру
    echo '</pre>';
} else {
    echo "Ошибка $httpCode: $response";
}