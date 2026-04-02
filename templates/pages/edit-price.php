<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    $cur_id = $_POST['unp_id']; 

	$res = $db_connect->query('SELECT * FROM price WHERE id = ' . $cur_id);	    
	  
	$row = $res->fetchAssoc();
  
	$result = array(
		'id'   => $row['id'],
		'user_id'   => $row['user_id'],
		'city_id'   => $row['city_id'],
		'amount'   => $row['amount']
	);

	echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>