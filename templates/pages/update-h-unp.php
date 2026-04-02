<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


    $cur_id = $_POST['idval'];
    $i_fio = $_POST['fio'];
    $i_phone_number = $_POST['phone_number'];
    $i_vopros = $_POST['vopros'];
    $i_address = $_POST['address'];
    $i_city = $_POST['city'];
    $i_status = $_POST['status'];
    $i_date_create = $_POST['date_create'];
    $i_timez = $_POST['timez'];
    $i_user_id = $_POST['user_name'];
    $i_date_time_status_change = $_POST['date_time_status_change'];

	if($_POST["fio"]) {$i_sql1 = 'fio  = "' . $i_fio . '", ';}
	if($_POST["phone_number"]) {$i_sql2 = 'phone_number  = "' . $i_phone_number . '", ';}
	if($_POST["vopros"]) {$i_sql3 =  'vopros  = "' . $i_vopros . '", ';}
	if($_POST["address"]) {$i_sql4 =  'address  = "' . $i_address . '", ';}
	if($_POST["city"]) {$i_sql5 =  'city  = "' . $i_city . '", ';}
	if($_POST["status"]) {$i_sql6 =  'status  = "' . $i_status . '", ';}
	if($_POST["date_create"]) {$i_sql7 =  'date_create = "' . $i_date_create . '", ';}
	if($_POST["timez"]) {$i_sql8 =  'timez = "' . $i_timez . '", ';}
	if($_POST["user_name"]) {$i_sql9 =  'user_id = "' . $i_user_id . '", ';}
	if($_POST['date_time_status_change']) {
		$formatted_date = str_replace('T', ' ', $i_date_time_status_change) . ":00";
		$i_sql10 =  'date_time_status_change = "' . $formatted_date . '", ';
	}
	else {
		$i_sql10 =  'date_time_status_change = "", ';
	}
	$i_sql11 =  'is_dubl = "N", ';
	$i_row_change_time = date("Y-m-d");
	$res_sql = $db_connect->query('SELECT count(*) as cnt FROM `'. BEZ_DBPREFIX .'unprocessed` WHERE id = ' . $cur_id . ' AND status = "1" AND user_id = "' . $i_user_id . '"');
	$res_sql2 = $db_connect->query('SELECT count(*) as cnt, leads, coming, row_change_time FROM statistic WHERE user_id = "' . $i_user_id . '"' );
	$res_sql3 = $db_connect->query('SELECT count(*) as cnt FROM `'. BEZ_DBPREFIX .'unprocessed` WHERE id = ' . $cur_id . ' AND is_dubl = "Y" ');	
	$res_data = $res_sql->fetchAssoc();
	$res_cnt = $res_sql2->fetchAssoc();
	$res_dubl = $res_sql3->fetchAssoc();
	
	if ($res_cnt["cnt"] == 0 and $res_data == 0 and ($_POST["status"] <> '8' or $_POST["status"] <> '9')) {
		$db_connect->query('INSERT INTO statistic ( user_id, leads, coming, date_create, row_change_time, leads_id ) VALUES ("' . $i_user_id . '", "1", "0", "' . $i_date_create . '", "' . $i_row_change_time . '", "' . $cur_id . '")');	    
	}
	if ($res_cnt["cnt"] > 0 and $res_data > 0 and $_POST["status"] == '4' ) {
		$db_connect->query('UPDATE statistic SET coming = ' . $res_cnt["coming"] + 1 . ', row_change_time ="' . $i_row_change_time . '" WHERE user_id = "' . $i_user_id . '"');	    
	}
	if ($res_cnt["cnt"] > 0 and $res_data > 0 and ($_POST["status"] <> '8' or $_POST["status"] <> '9') ) {
		$db_connect->query('UPDATE statistic SET leads = ' . $res_cnt["leads"] + 1 . ', row_change_time ="' . $i_row_change_time . '" WHERE user_id = "' . $i_user_id . '"');	    
	}
    if ($res_dubl > 0) {
		$i_sql = 'UPDATE `'. BEZ_DBPREFIX .'unprocessed` SET ' . $i_sql1 . $i_sql2 . $i_sql3 . $i_sql4 . $i_sql5 . $i_sql6 . $i_sql7 . $i_sql8 . $i_sql9 . $i_sql10 . $i_sql11 . 'id = "' . $cur_id . '" WHERE id = ' . $cur_id;	
		$db_connect->query($i_sql);	
	} else {
		$i_sql = 'UPDATE `'. BEZ_DBPREFIX .'unprocessed` SET ' . $i_sql1 . $i_sql2 . $i_sql3 . $i_sql4 . $i_sql5 . $i_sql6 . $i_sql7 . $i_sql8 . $i_sql9 . $i_sql10 . 'id = "' . $cur_id . '" WHERE id = ' . $cur_id;	
		$db_connect->query($i_sql);			
	}
    $i_text = $i_sql1 . $i_sql2 . $i_sql3 . $i_sql4 . $i_sql5 . $i_sql6 . $i_sql7 . $i_sql8 . $i_sql9 . 'id = "' . $cur_id;
	$i_text = str_replace(',',';',$i_text);
	$i_text = str_replace('"','',$i_text);
    $i_value = 'update';

	$db_connect->query('INSERT INTO logs ( row_change_time, value, text, user_id) VALUES ("' .$i_row_change_time . '", "' .$i_value . '", "' .$i_text . '", "' .$i_user_id . '")');	    
				  
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>