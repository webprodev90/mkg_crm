<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    $i_fio = $_POST['fio'];
    $i_phone_number = $_POST['phone_number'];
    $i_vopros = $_POST['vopros'];
    $i_city = $_POST['city'];
    $i_created_by_user_id = $_POST['user'];
    
    $i_date_create = date("Y-m-d");
    $i_date_create_comment = date("Y-m-d H:i:s"); 

	$i_status = $_POST['status'];
	
	$i_phone_number_clear = clean_phone_number_cod($i_phone_number, '7');
	
	$i_sql_duble = $db_connect->query('SELECT count(1) as cnt FROM bez_unprocessed_base2 WHERE SUBSTR(REPLACE(REPLACE(REPLACE(REPLACE(phone_number,"-",""),")",""),"(","")," ",""),-10) = "' . substr($i_phone_number_clear,-10) . '" ;');
	$row2 = $i_sql_duble->fetchAssoc();	
	
	
	if ( $row2["cnt"] > 0) {
		$i_status = '19';
	}
	
	
	$i_sql = 'INSERT INTO `'. BEZ_DBPREFIX .'unprocessed_base2` (`fio`, `phone_number`, `city`, `status`, `date_create`, `created_by_user_id`) VALUES ("' . $i_fio . '", "' . $i_phone_number_clear . '", "' . $i_city . '", "' . $i_status . '", "' . $i_date_create . '", "' . $i_created_by_user_id . '");';
	$db_connect->query($i_sql);	

	$queryset = $db_connect->query('SELECT LAST_INSERT_ID() AS id;');
	$row = $queryset->fetchAssoc();

	$i_sql2 = 'INSERT INTO `'. BEZ_DBPREFIX .'unprocessed_base2_comments` (`request2_id`, `comment`, `date_create`) VALUES ("' . $row["id"] . '", "' . $i_vopros . '", "' . $i_date_create_comment . '")';	
	$db_connect->query($i_sql2);	
					  
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>