<?php
/*header('Content-Type: application/json');
file_put_contents('test.log', print_r($_POST, true)); // Для проверки входных данных
echo json_encode(['status' => 'success']);
*/

header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


// Логирование входных данных
file_put_contents('test.log', print_r($_POST, true));

// Получаем данные
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (empty($data) && !empty($_POST)) {
    $data = $_POST;
}

if (empty($data)) {
    echo json_encode(['status' => 'error', 'message' => 'Нет данных для обработки']);
    exit;
}

// Извлекаем поля (аналогично предыдущему варианту)
$formId = $data['form']['id'];
$formName = $data['form']['name'];

$phone = $data['fields']['field_df93620']['value'];

$privacyPolicyConsent = 0;
if ($data['fields']['field_e396786']['value'] == 1) {
        $privacyPolicyConsent = 1;
}

//$fieldsJson = json_encode($data['fields'] ?? [], JSON_UNESCAPED_UNICODE);
$fieldsJson = json_encode($data['fields'], JSON_UNESCAPED_UNICODE);

// Дата (конвертируем DD.MM.YYYY → YYYY-MM-DD)
$submissionDate = date('Y-m-d');
if (!empty($data['meta']['date']['value'])) {
    $dateParts = explode('.', $data['meta']['date']['value']);
    if (count($dateParts) === 3) {
        $submissionDate = sprintf('%04d-%02d-%02d', (int)$dateParts[2], (int)$dateParts[1], (int)$dateParts[0]);
    }
}

$submissionTime = $data['meta']['time']['value'];
$pageUrl = $data['meta']['page_url']['value'];
$remoteIp = $data['meta']['remote_ip']['value'] ?? $_SERVER['REMOTE_ADDR'];
$userAgent = $data['meta']['user_agent']['value'] ?? $_SERVER['HTTP_USER_AGENT'];


// Формируем SQL-запрос вручную (с экранированием)
$sql = "INSERT INTO form_submissions 
    (form_id, form_name, phone, privacy_policy_consent, fields, submission_date, submission_time, page_url, remote_ip, user_agent)
    VALUES
    ('$formId', '$formName', '$phone', $privacyPolicyConsent, '$fieldsJson', '$submissionDate', '$submissionTime', '$pageUrl', '$remoteIp', '$userAgent')";



// Выполняем запрос
if ($db_connect->query($sql) === TRUE) {
    $insertId = $db_connect->insert_id;
    echo json_encode([
        'status' => 'success',
        'message' => 'Данные успешно сохранены',
        'insert_id' => $insertId
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Ошибка выполнения запроса: ' . $db_connect->error
    ]);
}

$sqlUpdate = "UPDATE bez_unprocessed_base SET is_sog = 1 WHERE phone_number = " . substr($phone, -10);
// Выполняем обновление
if ($db_connect->query($sqlUpdate) === TRUE) {
    $insertId2 = $db_connect->insert_id;
    echo json_encode([
        'status' => 'success',
        'message' => 'Данные успешно обновлены',
        'insert_id' => $insertId2
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Ошибка обновления запроса: ' . $db_connect->error
    ]);
}

?>
