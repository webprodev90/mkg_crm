<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

if(empty($_POST['id'])) {
	http_response_code(400);
	header('Content-Type: application/json');
	echo json_encode(array('error' => 'Параметр "id" отсутствует или пустой в запросе'));
	exit;
}


$id = $_POST['id'];
unset($_POST['id']);
$params = $_POST;

$query_params = implode(', ', array_map(function ($key, $value) {
	return trim("$key = '$value'");
}, array_keys($params), $params));

$dbprefix = BEZ_DBPREFIX;
$current_date = date("Y-m-d");
$user_id = $_SESSION['login_id'];

$query_request = "
    UPDATE {$dbprefix}unprocessed_base SET {$query_params} WHERE id = {$id}
";

$query_log = "
	INSERT INTO logs 
	(row_change_time, value, text, user_id)
		VALUES 
	('{$current_date}', 'update', '{$query_request}', '{$user_id}')
";

$db_connect->query($query_request);

$queryset_result = $db_connect->query(
	"
	SELECT 
		{$dbprefix}unprocessed_base.fio,
		{$dbprefix}unprocessed_base.phone_number,
		{$dbprefix}unprocessed_base.vopros,
		{$dbprefix}unprocessed_base.city,
		{$dbprefix}unprocessed_base.status,
		{$dbprefix}unprocessed_base.date_create,
		{$dbprefix}unprocessed_base.partner,
		{$dbprefix}status.status_name,
		{$dbprefix}unprocessed_base.user_id as operator_id

	FROM {$dbprefix}unprocessed_base
	LEFT JOIN {$dbprefix}status ON {$dbprefix}status.status_id = {$dbprefix}unprocessed_base.status
	LEFT JOIN {$dbprefix}reg ON {$dbprefix}reg.id = {$dbprefix}unprocessed_base.user_id
		WHERE {$dbprefix}unprocessed_base.id = {$id}"
);


$result = $queryset_result->fetchAssoc();

http_response_code(200);
header('Content-Type: application/json');
echo json_encode($result);
?>