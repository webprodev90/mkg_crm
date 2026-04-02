<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';               


  if(!empty($_POST['unp_id']))
  { 
    $cur_id = $_POST['unp_id'];
    $cur_id2 = $_POST['unp_id2'];
    $cur_id3 = $_POST['unp_id3'];	

	$res = $db_connect->query("SELECT DISTINCT bez_unprocessed_base.id,
											DATE_FORMAT(bsr1.date_time, '%d.%m.%Y') as date_time_d,
											TIME_FORMAT(bsr1.date_time, '%H:%i:%S') as date_time_t,
									        bez_unprocessed_base.fio,
									        CONCAT('7', bez_unprocessed_base.phone_number) AS phone_number
									FROM bez_sale_request bsr1
									JOIN bez_unprocessed_base 
									ON bez_unprocessed_base.id = bsr1.request_id
									WHERE bsr1.date_time BETWEEN '{$cur_id2} 00:00:00' AND '{$cur_id3} 23:59:59'         
									AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr1.partner_id <> 65 and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
									AND bsr1.partner_id = {$cur_id}
									UNION ALL 
									SELECT bez_unprocessed_base.id,
									     bez_unprocessed_base.date_time_of_last_save as date_time_d,
									     bez_unprocessed_base.date_time_of_last_save as date_time_t,
									     bez_unprocessed_base.fio,
									     bez_unprocessed_base.phone_number
									FROM bez_unprocessed_base 
									WHERE bez_unprocessed_base.is_ship = '1' AND DATE_FORMAT(bez_unprocessed_base.date_create, '%Y-%m-%d') between DATE_FORMAT('{$cur_id2}', '%Y-%m-%d') and DATE_FORMAT('{$cur_id3}', '%Y-%m-%d') AND bez_unprocessed_base.source = 'telegram' AND bez_unprocessed_base.partner = {$cur_id} AND bez_unprocessed_base.user_id <> ''
									ORDER BY id ASC;
								");	    

		$delimiter = ";"; 
		$buffer = fopen(__DIR__ . '/uploads/partner_' . $cur_id . '.csv', 'w'); 
		// Добавляет в начало файла метку BOM, благодаря этому файл откроется в Excel с нормальной кодировкой.
		fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
		// Шапка
		$fields = array('Дата отгрузки','Время отправки','ФИО', 'Телефон'); 
		fputcsv($buffer, $fields, $delimiter); 
		// Данные в файл csv
		while($row = $res->fetchAssoc()){ 
			$lineData = array( $row['date_time_d'], $row['date_time_t'], trim($row['fio']), trim($row['phone_number'])); 
			fputcsv($buffer, $lineData, $delimiter); 
		} 
		fclose($buffer); 

	echo 'https://crm.mkggroup.ru/templates/pages/uploads/partner_' . $cur_id . '.csv';
  }
	exit; 
?>