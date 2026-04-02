<?php
// Получаем данные от Битрикс24
$inputData = json_decode(file_get_contents('php://input'), true);
/*
// Проверяем подпись (если включена в настройках вебхука)
if (isset($_SERVER['HTTP_BX_HOOK_SIGNATURE'])) {
    $signature = $_SERVER['HTTP_BX_HOOK_SIGNATURE'];
    $computedSignature = hash_hmac('sha256', file_get_contents('php://input'), 'Ваш_Секретный_Ключ');
    
    if ($signature !== $computedSignature) {
        http_response_code(403);
        die('Invalid signature');
    }
}
*/
// Обрабатываем данные
if (!empty($inputData['event'])) {
    $event = $inputData['event'];
    $data = $inputData['data'];
    $file = $_SERVER['DOCUMENT_ROOT'] . '/bitrix24/383/logs.txt';
    
    if ($event === 'ONCRMDEALADD') {
        $dealId = $data['FIELDS']['ID'];
        $dealTitle = $data['FIELDS']['TITLE'];
        file_put_contents($file, "Добавлен лид {$dealId}: {$dealTitle}\n", FILE_APPEND);
    }
    
    // Отправляем подтверждение
    echo json_encode(['success' => true]);
} else {
    http_response_code(400);
    die('Invalid data');
}
