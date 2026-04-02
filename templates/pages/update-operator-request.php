<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

function get_error($key)
{
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Параметр "' . $key . '" отсутствует или пустой в запросе'));
    exit;
}

if (empty($_POST['operator_id'])) {
    get_error('operator_id');
}

if (empty($_POST['request_id'])) {
    get_error('request_id');
}

$user_id = $_POST['operator_id'];
$request_id = $_POST['request_id'];

$prefix = BEZ_DBPREFIX;

$queryset = $db_connect->query("
    UPDATE {$prefix}unprocessed_base
        SET user_id = {$user_id}
    WHERE id = {$request_id}
");