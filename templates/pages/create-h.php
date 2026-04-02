<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    $i_fio = $_POST['fio'];
    $i_phone_number = clean_phone_number_cod($_POST['phone_number'], '');
    $i_vopros = $_POST['vopros'];
    $i_address = $_POST['address'];
    $i_city = $_POST['city'];
    $i_otdel = $_POST['otdel'];
    $i_urlc = $_POST['urlc'];
	$i_lodin_id = $_POST['user'];
    
    $i_date_create = $_POST['date_create'];

	if($_POST["date_create"]) { $i_date_create =  $i_date_create; } else {$i_date_create = date("Y-m-d");}
	
	if($i_urlc > 0) {
		$i_status = '1';	
		$i_sql = 'INSERT INTO `'. BEZ_DBPREFIX .'unprocessed` (`user_id`, `fio`, `phone_number`, `vopros`, `city`, `status`, `date_create`) VALUES ("' . $i_lodin_id . '", "' . $i_fio . '", "' . $i_phone_number . '", "' .  $i_vopros . '", "' . $i_city . '", "' . $i_status . '", "' . $i_date_create . '")';	
	} else {
		$i_status = '10';	
		$i_sql = 'INSERT INTO `'. BEZ_DBPREFIX .'unprocessed_base` (`fio`, `phone_number`, `vopros`, `city`, `status`, `date_create`, `id_otdel`) VALUES ("' . $i_fio . '", "' . $i_phone_number . '", "' .  $i_vopros . '", "' . $i_city . '", "' . $i_status . '", "' . $i_date_create . '", "' . $i_otdel . '")';	
	}
	$db_connect->query($i_sql);	

	//echo '<pre>'; print_r($_POST); echo '</pre>';						  
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>