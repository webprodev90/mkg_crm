<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    $user_id = $_POST['user_id']; 
    $cur_id = $_POST['unp_id']; 
    $date_start = $_POST['date_start']; 
    $date_end = $_POST['date_end']; 
	
	
	$res = $db_connect->query('SELECT * FROM st_report_user WHERE id = ' . $cur_id);	    
	  
	$row = $res->fetchAssoc();
	
	if (isset($row['work_hours'])) { $work_hours = $row['work_hours']; } else { $work_hours = 8; }
	
	$result = array(
		'id'   => $cur_id,
		'user_id'   => $user_id,
		'work_hours'   => $work_hours,
		'date_start'   => $date_start,
		'date_end'   => $date_end
	);

	echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>