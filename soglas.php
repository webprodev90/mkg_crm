<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключение базы
require_once '/var/www/u375143/data/www/crm.mkggroup.ru/inc/config.php';
require_once '/var/www/u375143/data/www/crm.mkggroup.ru/inc/func/funct.php';
require_once '/var/www/u375143/data/www/crm.mkggroup.ru/inc/bd/bd.php';

/*
Настройки
v_status - Статус (можно несколько значений через запятую)
v_limit - по сколько записей
*/
$v_status = '1,22,8'; // 1 Не обработано, 22 АО , 8 Недозвон
$v_limit = 11000; // 2000

// Запрос к БД: выборка номеров
$res = $db_connect->query(
    'SELECT phone_number FROM bez_unprocessed_base
     WHERE is_sog = 0
       AND status IN (' . $v_status . ')
       AND LENGTH(phone_number) > 9
     ORDER BY id desc
     LIMIT ' . $v_limit
);

// Собираем все номера в массив (добавляем префикс "7")
$phones = [];
while ($row = $res->fetchAssoc()) {
    $phones[] = '7' . $row['phone_number'];
}

// Путь к файлу
$targetFile = '/var/www/u375143/data/www/pzvonok.ru/phone.txt';

// Проверка прав на запись в директорию
if (!is_dir(dirname($targetFile)) || !is_writable(dirname($targetFile))) {
    http_response_code(500);
    echo 'Директория недоступна для записи';
    exit;
}

// Запись номеров в файл (сначала очищаем файл, потом записываем новые данные)
$content = implode(PHP_EOL, $phones) . PHP_EOL;

// Используем LOCK_EX для блокировки, но БЕЗ FILE_APPEND - файл перезапишется
$bytesWritten = file_put_contents($targetFile, $content, LOCK_EX);

if ($bytesWritten === false) {
    http_response_code(500);
    echo 'Ошибка записи в файл';
    exit;
}

// Логирование в таблицу lg_form_submissions
try {
    $sent_count = count($phones);
    $received_count = 0;

    $insertLog = $db_connect->query(
        'INSERT INTO lg_form_submissions (sent_rows, received_rows)
         VALUES (' . (int)$sent_count . ', ' . (int)$received_count . ')'
    );

    if (!$insertLog) {
        // Если вставка в лог не удалась — всё равно отдаём успех, но пишем в ошибки
        error_log('Не удалось записать лог в lg_form_submissions. Отправлено: ' . $sent_count);
    }
} catch (Exception $e) {
    error_log('Ошибка при логировании: ' . $e->getMessage());
}

// Ответ клиенту
http_response_code(200);
echo 'Успешно записано номеров: ' . $sent_count . ' (файл перезаписан)';
?>