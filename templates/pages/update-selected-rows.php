<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


if(isset($_POST['submit'])) {
	$select_rows = mb_substr($_POST['sel_rows'], 0, -1);
	$count_rows = $_POST['count_rows'];
	$department = $_POST['department'];

   /*Если отметили хоть один чекбокс */
   if ($select_rows !== '') {	
		if ($_POST['submit'] == 'del') { /*Нажали удалить - удаляем*/
			$u_sql_d = "DELETE FROM bez_unprocessed_base_excel WHERE id in ({$select_rows})";
			$db_connect->query($u_sql_d);	
		} else {
			$i_sql = "INSERT INTO bez_unprocessed_base(fio, phone_number, vopros, address, city, auto_city_group, status, date_create, date_time_status_change, timez, user_id, partner, source, is_ship, id_otdel, group_source, date_time_of_last_save, past_status, is_double, manual, mobile_operator_id)
					SELECT fio, phone_number, vopros, address, city, auto_city_group, status, date_create, date_time_status_change, timez, user_id, partner, source, is_ship, {$department} as id_otdel, group_source, date_time_of_last_save, past_status, is_double, manual, mobile_operator_id FROM bez_unprocessed_base_excel
					WHERE id in ({$select_rows})";
			$db_connect->query($i_sql);			
			
			$u_sql_d = "DELETE FROM bez_unprocessed_base_excel WHERE id in ({$select_rows})";
			$db_connect->query($u_sql_d);				
		}
   
		//print_r($select_rows); 
   } /* Иначе по количеству записей */
   else {
		if ($_POST['submit'] == 'del' and $count_rows !== '') { /*Нажали удалить - удаляем*/
			$u_sql_d = "DELETE FROM bez_unprocessed_base_excel ORDER BY id DESC LIMIT {$count_rows}";
			$db_connect->query($u_sql_d);	
		} else {
			$i_sql = "INSERT INTO bez_unprocessed_base(fio, phone_number, vopros, address, city, auto_city_group, status, date_create, date_time_status_change, timez, user_id, partner, source, is_ship, id_otdel, group_source, date_time_of_last_save, past_status, is_double, manual, mobile_operator_id)
					SELECT fio, phone_number, vopros, address, city, auto_city_group, status, date_create, date_time_status_change, timez, user_id, partner, source, is_ship, {$department} as id_otdel, group_source, date_time_of_last_save, past_status, is_double, manual, mobile_operator_id FROM bez_unprocessed_base_excel
					ORDER BY id DESC 
					LIMIT {$count_rows}";
			$db_connect->query($i_sql);	
			
			$u_sql_d = "DELETE FROM bez_unprocessed_base_excel ORDER BY id DESC LIMIT {$count_rows}";
			$db_connect->query($u_sql_d);			
		}	   

   }
 

/*
	$u_sql = "UPDATE bez_unprocessed_base_excel
			SET id_otdel = {$department},
				city_group = {$geo}
			ORDER BY id DESC 
			LIMIT {$count_rows}";
	$db_connect->query($u_sql);	
*/	
}
				  
header('Location:'. $_SERVER['HTTP_REFERER']);

?>