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
								 FROM `'. BEZ_DBPREFIX .'reg` 
								WHERE id = ' . $cur_id . '
								' 
							  );	  
							  
	$row = $res->fetchAssoc();
  
	$result = array(
		'id'  => $row['id'],
		'address'   => $row['address_id'],
		'id_otdel'   => $row['id_otdel'],
		'name'   => $row['name'],
		'login'  => $row['login'],
		'id_atc'  => $row['id_atc'],
		'role'   => $row['role']
	);

	echo json_encode($result, JSON_UNESCAPED_UNICODE);
  }
  else
    {
      echo "Что-то пошло не так";
  }
?>