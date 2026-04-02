<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';
/* Подключение к Скоррингу */
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config4.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd4.php'; 

function clean_phone_number($phone_number) {
	$cleaned_phone_number = preg_replace('/\D/', '', $phone_number);

	if(preg_match('/^[87]/', $cleaned_phone_number)) {
		$cleaned_phone_number = substr($cleaned_phone_number, 1);
	}
	
	$cleaned_phone_number = substr($cleaned_phone_number, -10);

	return $cleaned_phone_number;
}

  if(!empty($_POST['atc_id'])) {
    $atc_id = $_POST['atc_id'];
	$cur_phone_number = $_SESSION['phone_number'];


	$res3 = $db_connect4->query('SELECT `number` FROM `connections` WHERE vnum = "' . $atc_id . '" ORDER BY `id` DESC LIMIT 1');
	$row3 = $res3->fetchAssoc();	  
		
	if ($row3['number'] != $cur_phone_number) {
		
		$new_phone_number = clean_phone_number($row3['number']);
		$_SESSION['phone_number'] = $row3['number'];
		session_write_close();
		$res4 = $db_connect->query("SELECT bez_unprocessed_base.id, fio, phone_number, city, status, IFNULL(bez_cities_group.name, '') AS region, debt_banks, debt_mfo, taxes_fines, debt_zhkh, owners, delays, mortgage, car_loan, IFNULL(other_movables, '') AS other_movables, IFNULL(other_early_action, '') AS other_early_action, IFNULL(messenger_phone_number, '') AS messenger_phone_number, date_time_status_change, GROUP_CONCAT(DISTINCT request_real_estate.real_estate_id) AS selected_real_estate, GROUP_CONCAT(DISTINCT request_movables.movables_id) AS selected_movables, GROUP_CONCAT(DISTINCT request_early_action.early_action_id) AS selected_early_action, GROUP_CONCAT(DISTINCT request_messengers.messenger_id) AS selected_messengers
                                        FROM bez_unprocessed_base 
                                        LEFT JOIN bez_cities_group
                                        ON bez_unprocessed_base.auto_city_group = bez_cities_group.id
                                        LEFT JOIN request_real_estate
                                        ON bez_unprocessed_base.id = request_real_estate.request_id
                                        LEFT JOIN request_movables
                                        ON bez_unprocessed_base.id = request_movables.request_id
                                        LEFT JOIN request_early_action
                                        ON bez_unprocessed_base.id = request_early_action.request_id
                                        LEFT JOIN request_messengers
                                        ON bez_unprocessed_base.id = request_messengers.request_id
									WHERE bez_unprocessed_base.phone_number like '%{$new_phone_number}'
									HAVING bez_unprocessed_base.id IS NOT NULL 
									ORDER BY bez_unprocessed_base.id DESC LIMIT 1");
		if(DEBUG == 'y') {
			$db_connect->query('INSERT INTO logs (row_change_time, value, modul, text, user_id )
								   VALUES( NOW(),
								   		   "search",
										   "atc_call",
										   "phone_number = ' . $new_phone_number . '",
										   "' . $atc_id . '"
											)');			
		}
		if ($res4->getNumRows() > 0) {
			$row4 = $res4->fetchAssoc();
			header('Content-Type: application/json');
			ob_end_clean(); 
			echo json_encode($row4, JSON_UNESCAPED_UNICODE);
		} else {
			echo 1; // Нет номера
		}

		//print_r($row4);
		//return $rows;
	} else {
		echo 0;
	}	
	
			
	//header('Location:'. $_SERVER['HTTP_REFERER']);
  } else {
      echo "Что-то пошло не так";
  }



?>