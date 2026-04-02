<?php
// URL обработчика вебхука
$webhook_url = 'https://crm.mkggroup.ru/bitrix24/383/webhook_handler.php';

// Данные, которые Битрикс24 отправляет
$payload = [
    'event' => 'ONCRMDEALADD',
    'data' => [
        'FIELDS' => [
            'ID' => '12222',
            'NAME' => 'Иван',
            'LAST_NAME' => 'Иванов',
            'PHONE' => [
                ['VALUE' => '+79001234567', 'VALUE_TYPE' => 'WORK']
            ],
            'EMAIL' => [
                ['VALUE' => 'test@example.com', 'VALUE_TYPE' => 'WORK']
            ]
        ]
    ]
];

$json_payload = json_encode($payload);

// Настройка cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $webhook_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $json_payload,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json'
    ],
    CURLOPT_SSL_VERIFYPEER => false, 
    CURLOPT_HEADER => true 
]);

// Выполнение запроса
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Проверка ошибок
if (curl_errno($ch)) {
    echo 'Ошибка cURL: ' . curl_error($ch);
} else {
    echo "HTTP код: $http_code\n";
    echo "Ответ сервера:\n" . $response;
}

curl_close($ch);