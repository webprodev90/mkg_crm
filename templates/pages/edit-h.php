<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


  if(!empty($_POST['unp_id']))
  {
    $cur_id = $_POST['unp_id'];

	$res = $db_connect->query('SELECT * 
								 FROM `'. BEZ_DBPREFIX .'unprocessed` 
								WHERE id = ' . $cur_id . '
								' 
							  );	    
	  
	$row = $res->fetchAssoc();
  
	$result = array(
	'id'  => $row['id'],
	'fio'  => $row['fio'],
	'phone_number'  => $row['phone_number'],
	'vopros'  => $row['vopros'],
	'address'  => $row['address'],
	'city'  => $row['city'],
	'status'  => $row['status'],
	'user_name' => $row['user_id'],
	'date_time_status_change' => $row['date_time_status_change']
	);
 
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
  }
  else
    {
      echo "Что-то пошло не так";
  }



?>