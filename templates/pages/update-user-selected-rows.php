<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


if(isset($_POST['submit'])) {
	$select_rows = mb_substr($_POST['sel_rows'], 0, -1);
	$count_rows = $_POST['count_rows'];
	$user_id = $_POST['user_id'];
	$id_otdel = $_POST['id_otdel'];
	$login_role = $_POST['login_role'];
	$where_otdel = '';

	if(isset($_POST["selected_rows_status"])) {

		if($login_role == 4) {
			$where_otdel = ", id_otdel = {$id_otdel}";
		}

		if($_POST["selected_rows_status"] == 32) {
		   	if($select_rows !== '') {	

				$u_sql = "UPDATE bez_unprocessed_base
							SET user_id = {$user_id}, date_time_of_last_save = NOW(){$where_otdel}
							WHERE id in ({$select_rows})";
				$db_connect->query($u_sql);							

		   	}				
		}

	} else {

		if($login_role == 4) {
			$where_otdel = " and id_otdel = {$id_otdel}";
		}

	   /*Если отметили хоть один чекбокс */
	   	if($select_rows !== '') {	

			$u_sql = "UPDATE bez_unprocessed_base
						SET user_id = {$user_id}
						WHERE id in ({$select_rows})";
			$db_connect->query($u_sql);							

	   	} /* Иначе по количеству записей */
	   	else {

			$u_sql = "UPDATE bez_unprocessed_base
						SET user_id = {$user_id}
						WHERE is_double IS NULL and manual = '' {$where_otdel}
						ORDER BY id DESC 
						LIMIT {$count_rows}";
			$db_connect->query($u_sql);

	   	}
	   	
 	}
}
				  
header('Location:'. $_SERVER['HTTP_REFERER']);

?>