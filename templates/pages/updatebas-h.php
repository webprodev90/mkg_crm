<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


    $cur_id = $_POST['idval'];
	if(isset($_POST['fio'])){ $i_fio = $_POST['fio'];}
	if(isset($_POST['phone_number'])){ $i_phone_number = $_POST['phone_number'];}
	if(isset($_POST['vopros'])){ $i_vopros = $_POST['vopros'];}
	if(isset($_POST['partner'])){ $i_partner = $_POST['partner'];}
	if(isset($_POST['city'])){ $i_city = $_POST['city'];}
	if(isset($_POST['status'])){ $i_status = $_POST['status'];}
	if(isset($_POST['date_create'])){ $i_date_create = $_POST['date_create'];}
	if(isset($_POST['timez'])){ $i_timez = $_POST['timez'];}
	if(isset($_POST['user_name'])){ $i_user_id = $_POST['user_name'];}


	if(isset($i_fio)) {$i_sql1 = 'fio  = "' . $i_fio . '", ';} else { $i_sql1 = '';}
	if(isset($i_phone_number)) {$i_sql2 = 'phone_number  = "' . $i_phone_number . '", ';} else { $i_sql2 = '';}
	if(isset($i_vopros)) {$i_sql3 =  'vopros  = "' . $i_vopros . '", ';} else { $i_sql3 = '';}
	if(isset($i_partner)) {$i_sql4 =  'partner  = "' . $i_partner . '", ';} else { $i_sql4 = '';}
	if(isset($i_partner)) {$i_sql5 =  'is_ship  = "1", ';} else { $i_sql5 = '';}
	if(isset($i_status)) {$i_sql6 =  'status  = "' . $i_status . '", ';} else { $i_sql6 = '';}
	if(isset($i_date_create)) {$i_sql7 =  'date_create = "' . $i_date_create . '", ';} else { $i_sql7 = '';}
	if(isset($i_timez)) {$i_sql8 =  'timez = "' . $i_timez . '", ';} else { $i_sql8 = '';}
	if(isset($i_user_id)) {$i_sql9 =  'user_id = "' . $i_user_id . '", ';} else { $i_sql9 = '';}
	$i_sql10 =  'source = "telegram", ';
	$i_row_change_time = date("Y-m-d");


	$i_sql = 'UPDATE `'. BEZ_DBPREFIX .'unprocessed_base` SET ' . $i_sql1 . $i_sql2 . $i_sql3 . $i_sql4 . $i_sql5 . $i_sql6 . $i_sql7 . $i_sql8 . $i_sql9 . $i_sql10 . 'id = "' . $cur_id . '" WHERE id = ' . $cur_id;
	$db_connect->query($i_sql);

	$res = $db_connect->query('SELECT * FROM `'. BEZ_DBPREFIX .'unprocessed_base` WHERE id = "' . $cur_id . '"');
	$row = $res->fetchAssoc();
	$res2 = $db_connect->query('SELECT * FROM `st_partner_s` WHERE id = "' . $row["partner"] . '"');
	$row2 = $res2->fetchAssoc();
	$row3 = []; 


	in_caller($row, $row2, $row3);
/*
	$result = in_caller($row, $row2);
	$db_connect->query('INSERT INTO logs (row_change_time, value, modul, text )
								   VALUES( NOW(),
								   		   "curl",
										   "in_caller",
										   "result = ' . $result . '"
											)');

*/
	$res0 = $db_connect->query('SELECT * FROM `bez_reg` WHERE id = "' . $row2["user_id"] . '"');
	$role = $res0->fetchAssoc();

if ( $role["role"] == '6') {
	$res3 = $db_connect->query('SELECT * FROM `balance` WHERE user_id = "' . $row2["user_id"] . '"');
	$balance = $res3->fetchAssoc();

	$res4 = $db_connect->query('SELECT * FROM `st_city_s` WHERE REPLACE(REPLACE(REPLACE(REPLACE(name_city, " ", "" ), " ", "" ), "\r", ""), "\n", "") = REPLACE(REPLACE(REPLACE(REPLACE("' . $row["city"] . '", " ", "" ), " ", "" ), "\r", ""), "\n", "")');
	$city = $res4->fetchAssoc();

	$res5 = $db_connect->query('SELECT * FROM `price` WHERE user_id = "' . $row2["id"] . '" AND city_id = "' . $city["id"] . '"');
	$price = $res5->fetchAssoc();

	$bal = $balance["amount"] - $price["amount"];

	$v_sql = 'UPDATE `balance` SET amount = '. $bal .'  WHERE user_id = "' . $row2["user_id"] . '"';
	$db_connect->query($v_sql);
}

//для вкладки Партнеры в блоке Продажи

//для отбраковки
//находим всех партнеров, у которых есть дата закрытия1 и нет даты закрытия2
$res_partners_plan1 = $db_connect->query("
	SELECT bez_partners_plan.*
	FROM bez_partners_plan 
	WHERE bez_partners_plan.partner_id = {$row['partner']} AND bez_partners_plan.date_start IS NOT NULL AND bez_partners_plan.date_start <= CURDATE() AND bez_partners_plan.date_end1 IS NOT NULL AND bez_partners_plan.date_end2 IS NULL 
	GROUP BY bez_partners_plan.id;
	");

$results_partners_plan1 = [];
while($row_partners_plan = $res_partners_plan1->fetchAssoc()) {
    $results_partners_plan1[] = $row_partners_plan;
}

if(count($results_partners_plan1) !== 0) {
    foreach ($results_partners_plan1 as $row_partners_plan) {
    	//вставляем строку с текущей датой и id продаваемого лида 
        $i_sql4 = "
            INSERT INTO bez_partners_plan_dates (partner_plan_id, date, stage, request_id)
            SELECT '{$row_partners_plan['id']}', CURDATE(), 2, '{$cur_id}' WHERE NOT EXISTS (
            SELECT 1 FROM bez_partners_plan_dates WHERE partner_plan_id = {$row_partners_plan['id']} AND date = CURDATE() AND stage = 2 AND request_id = {$cur_id}
            );
        "; 

        $db_connect->query($i_sql4);

        //узнаем кол-во отгруженных лидов
        $results_partners_plan11 = null;
        $res_partners_plan11 = $db_connect->query("	
			SELECT COUNT(bez_partners_plan_dates.request_id) AS shipped2 
			FROM bez_partners_plan JOIN bez_partners_plan_dates
			ON bez_partners_plan_dates.partner_plan_id = bez_partners_plan.id
			WHERE bez_partners_plan.id = {$row_partners_plan['id']} AND bez_partners_plan_dates.stage = 2 AND DATE(bez_partners_plan.date_end1) <= bez_partners_plan_dates.date;
		");   
		$results_partners_plan11 = $res_partners_plan11->fetchAssoc();

		//если кол-во отгруженных лидов больше/равно отбраковке, то ставим дату закрытия2
		if($results_partners_plan11 !==  null) {
	        if((int) $row_partners_plan['otbrakovka'] <= (int) $results_partners_plan11['shipped2']) {
	        	$query_request4 = "
	            	UPDATE bez_partners_plan SET date_end2 = NOW() WHERE id = {$row_partners_plan['id']}
	        	";         
	        	$db_connect->query($query_request4);
	        }   			
		}
       
    }                     
}

//для общего количества лидов
//находим всех партнеров, у которых есть дата открытия и она уже началась, и нет даты закрытия1
$res_partners_plan2 = $db_connect->query("
	SELECT bez_partners_plan.*
	FROM bez_partners_plan
	WHERE bez_partners_plan.partner_id = {$row['partner']} AND bez_partners_plan.date_start IS NOT NULL AND bez_partners_plan.date_start <= CURDATE() AND bez_partners_plan.date_end1 IS NULL
	GROUP BY bez_partners_plan.id;
	");

$results_partners_plan2 = [];
while($row_partners_plan = $res_partners_plan2->fetchAssoc()) {
    $results_partners_plan2[] = $row_partners_plan;
}

if(count($results_partners_plan2) !== 0) {
    foreach ($results_partners_plan2 as $row_partners_plan) {
    	//вставляем строку с текущей датой и id продаваемого лида 
        $i_sql2 = "
            INSERT INTO bez_partners_plan_dates (partner_plan_id, date, stage, request_id)
            SELECT '{$row_partners_plan['id']}', CURDATE(), 1, '{$cur_id}' WHERE NOT EXISTS (
            SELECT 1 FROM bez_partners_plan_dates WHERE partner_plan_id = {$row_partners_plan['id']} AND date = CURDATE() AND stage = 1 AND request_id = {$cur_id}
            );
        "; 

        $db_connect->query($i_sql2);

        //узнаем кол-во отгруженных лидов
        $results_partners_plan21 = null;
        $res_partners_plan21 = $db_connect->query("	
        	SELECT COUNT(bez_partners_plan_dates.request_id) AS shipped1
			FROM bez_partners_plan JOIN bez_partners_plan_dates
			ON bez_partners_plan_dates.partner_plan_id = bez_partners_plan.id
			WHERE bez_partners_plan.id = {$row_partners_plan['id']} AND bez_partners_plan.date_start <= bez_partners_plan_dates.date AND bez_partners_plan_dates.stage = 1;
		");   
		$results_partners_plan21 = $res_partners_plan21->fetchAssoc();

        //если кол-во отгруженных лидов больше/равно плановому кол-ву лидов, то ставим дату закрытия1
        if($results_partners_plan21 !== null) {
	        if((int) $row_partners_plan['total_quantity'] <= (int) $results_partners_plan21['shipped1']) {
	        	$query_request = "
	            	UPDATE bez_partners_plan SET date_end1 = NOW() WHERE id = {$row_partners_plan['id']}
	        	";         
	        	$db_connect->query($query_request);
	        }       
        } 
    }                     
} 

/*
    $i_text = $i_sql1 . $i_sql2 . $i_sql3 . $i_sql4 . $i_sql5 . $i_sql6 . $i_sql7 . $i_sql8 . $i_sql9 . 'id = "' . $cur_id;
	$i_text = str_replace(',',';',$i_text);
	$i_text = str_replace('"','',$i_text);
    $i_value = 'update';

	$db_connect->query('INSERT INTO logs ( row_change_time, value, text, user_id) VALUES ("' .$i_row_change_time . '", "' .$i_value . '", "' .$i_text . '", "' .$i_user_id . '")');
*/
	//echo '<pre>'; print_r($_POST); echo '</pre>';
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>