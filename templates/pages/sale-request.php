<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


$cur_id = $_POST['idval'];
$partner_id = $_POST['partner_id'];

$new_data = '';
$text_warning = 'Продажа данному партнеру запрещена, так как'; 

$res_plan_check = $db_connect->query("
		SELECT 
		    SubTable.partner_id,
		    SubTable.count,
		    SubTable.date,
		    SubTable.partner_name,
		    SUM(SubTable.sub_sold_request) as sold_request,
		    IF(SUM(SubTable.sub_sold_request) >= SubTable.count, 0, 1) AS limit_count,
		    IF(DATE_ADD(NOW(), INTERVAL 1 HOUR) BETWEEN CONCAT(CURDATE(), ' ', SubTable.time_start) AND CONCAT(CURDATE(), ' ', IF(SubTable.time_end = '00:00:00', '23:59:59', SubTable.time_end)), 1, 0) AS time_compliance
		FROM 
		    (SELECT
		     	bez_plan.partner_id,
		     	bez_plan.count,
		     	bez_plan.date,
		        bez_plan.time_start,
		     	bez_plan.time_end,
		     	st_partner_s.partner_name,
		     	COUNT(bez_unprocessed_base.id) as sub_sold_request
		     FROM
		        bez_plan
		     JOIN
		        st_partner_s ON bez_plan.partner_id = st_partner_s.id
		     LEFT JOIN
		        bez_unprocessed_base
		        ON bez_unprocessed_base.partner = bez_plan.partner_id
		        AND bez_unprocessed_base.date_create = CURDATE()
		        AND bez_unprocessed_base.source = 'telegram'
		     WHERE
		        bez_plan.date = CURDATE() AND bez_plan.partner_id = {$partner_id}
		     GROUP BY
		        bez_plan.partner_id
		    UNION ALL     
		     SELECT
		        bez_plan.partner_id,
		        bez_plan.count,
		        bez_plan.date,
		        bez_plan.time_start,
		     	bez_plan.time_end,
		        st_partner_s.partner_name,
		        COUNT(DISTINCT bez_unprocessed_base.id) as sub_sold_request
		     FROM
		        bez_plan
		     JOIN
		        st_partner_s ON bez_plan.partner_id = st_partner_s.id
		     LEFT JOIN
		        bez_unprocessed_base
		        ON bez_unprocessed_base.partner = bez_plan.partner_id    
		     JOIN
		        bez_sale_request
		        ON bez_sale_request.request_id = bez_unprocessed_base.id
		        AND DATE(bez_sale_request.date_time) = CURDATE()      
		     WHERE
		        bez_plan.date = CURDATE() AND bez_plan.partner_id = {$partner_id}
		     GROUP BY
		        bez_plan.partner_id) AS SubTable
		GROUP BY
		    SubTable.partner_id;
	");
$plan_check = $res_plan_check->fetchAssoc();

if((!empty($plan_check) and $plan_check["limit_count"] and $plan_check["time_compliance"]) or ($partner_id == '65' or $partner_id == '102' or $partner_id == '140'))  {
	// if(isset($_POST['fio'])){ $i_fio = $_POST['fio'];}
	// if(isset($_POST['phone_number'])){ $i_phone_number = $_POST['phone_number'];}
	// if(isset($_POST['vopros'])){ $i_vopros = $_POST['vopros'];}
	// if(isset($_POST['partner'])){ $i_partner = $_POST['partner'];}
	// if(isset($_POST['city'])){ $i_city = $_POST['city'];}
	// if(isset($_POST['status'])){ $i_status = $_POST['status'];}
	// if(isset($_POST['date_create'])){ $i_date_create = $_POST['date_create'];}
	// if(isset($_POST['timez'])){ $i_timez = $_POST['timez'];}
	// if(isset($_POST['user_name'])){ $i_user_id = $_POST['user_name'];}


	// if(isset($i_fio)) {$i_sql1 = 'fio  = "' . $i_fio . '", ';} else { $i_sql1 = '';}
	// if(isset($i_phone_number)) {$i_sql2 = 'phone_number  = "' . $i_phone_number . '", ';} else { $i_sql2 = '';}
	// if(isset($i_vopros)) {$i_sql3 =  'vopros  = "' . $i_vopros . '", ';} else { $i_sql3 = '';}
	// if(isset($i_partner)) {$i_sql4 =  'partner  = "' . $i_partner . '", ';} else { $i_sql4 = '';}
	// if(isset($i_partner)) {$i_sql5 =  'is_ship  = "1", ';} else { $i_sql5 = '';}
	// if(isset($i_status)) {$i_sql6 =  'status  = "' . $i_status . '", ';} else { $i_sql6 = '';}
	// if(isset($i_date_create)) {$i_sql7 =  'date_create = "' . $i_date_create . '", ';} else { $i_sql7 = '';}
	// if(isset($i_timez)) {$i_sql8 =  'timez = "' . $i_timez . '", ';} else { $i_sql8 = '';}
	// if(isset($i_user_id)) {$i_sql9 =  'user_id = "' . $i_user_id . '", ';} else { $i_sql9 = '';}
	// $i_row_change_time = date("Y-m-d");


	// $i_sql = 'UPDATE `'. BEZ_DBPREFIX .'unprocessed_base` SET ' . $i_sql1 . $i_sql2 . $i_sql3 . $i_sql4 . $i_sql5 . $i_sql6 . $i_sql7 . $i_sql8 . $i_sql9 . 'id = "' . $cur_id . '" WHERE id = ' . $cur_id;
	// $db_connect->query($i_sql);

	$request_queryset = $db_connect->query('SELECT
												b.id,
												b.fio,
												b.phone_number,
												CASE
													WHEN p.is_audio = 1 AND a.link IS NOT NULL
													THEN CONCAT(b.vopros, " ", a.link)
													ELSE b.vopros
												END AS vopros,
												b.address,
												b.city,
												b.city_group,
												b.status,
												b.date_create,
												b.date_time_status_change,
												b.timez,
												b.user_id,
												b.partner,
												b.source,
												b.is_ship,
												b.id_otdel,
												b.group_source,
												b.date_time_of_last_save,
												b.past_status,
												b.is_double,
												b.manual,
												b.date_time_lead_save,
												b.date_time_of_first_touch,
												b.is_audio_check,
												b.auto_city_group,
												b.mobile_operator_id,
												b.debt_banks,
												b.debt_mfo,
												b.taxes_fines,
												b.debt_zhkh,
												b.owners,
												b.delays,
												b.mortgage,
												b.car_loan,
												b.other_movables,
												b.other_early_action,
												b.messenger_phone_number,
												b.additional_comment,
												b.hold_status_id,
												b.date_time_hold_calling,
												p.is_audio,
												a.link
											FROM bez_unprocessed_base b
											LEFT JOIN bez_fix_plan p
												ON p.partner_id = "' . $partner_id . '"
											LEFT JOIN audiorecordings a
												ON a.phone_number = b.phone_number
											 WHERE b.id = "' . $cur_id . '"');
	$reqeust = $request_queryset->fetchAssoc();

	$debt = (int) $reqeust["debt_banks"] + (int) $reqeust["debt_mfo"] + (int) $reqeust["taxes_fines"] + (int) $reqeust["debt_zhkh"];
	$debt = (string) $debt;
	$additional_comment = $reqeust["additional_comment"];

	$audio_link = "";
	if($reqeust["is_audio"] == 1 and !empty($reqeust["link"])) {
		$audio_link = $reqeust["link"];
	}

	$delays = "Не заполнено";
	$mortgage = "Не заполнено";
	$car_loan = "Не заполнено";

	if($reqeust["delays"] == "y") {
		$delays = "Да";
	} elseif($reqeust["delays"] == "n") {
		$delays = "Нет";
	}

	if($reqeust["mortgage"] == "m") {
		$mortgage = "Да+еще недвижимость";
	} elseif($reqeust["mortgage"] == "s") {
		$mortgage = "Да, единственная";
	} elseif($reqeust["mortgage"] == "n") {
		$mortgage = "Нет";
	}

	if($reqeust["car_loan"] == "y") {
		$car_loan = "Да";
	} elseif($reqeust["car_loan"] == "n") {
		$car_loan = "Нет";
	}

	$real_estate = [];
	$real_estate_str = "Нет";
	$res_real_estate = $db_connect->query("SELECT request_real_estate.id, real_estate.name FROM request_real_estate JOIN real_estate ON real_estate.id = request_real_estate.real_estate_id WHERE request_id = {$reqeust['id']}");					
	while($row_real_estate = $res_real_estate->fetchAssoc()) {	
		$real_estate[] = $row_real_estate["name"];
	}
	if(count($real_estate) !== 0) {
		$real_estate_str = implode(", ", $real_estate);
	}

	$movables = [];
	$movables_str = "Нет";
	$res_movables = $db_connect->query("SELECT request_movables.id, movables.name FROM request_movables JOIN movables ON movables.id = request_movables.movables_id WHERE request_id = {$reqeust['id']}");					
	while($row_movables = $res_movables->fetchAssoc()) {	
		$movables[] = $row_movables["name"];
	}
	if(!empty($reqeust["other_movables"])) {
		$movables[] = $reqeust["other_movables"];
	}
	if(count($movables) !== 0) {
		$movables_str = implode(", ", $movables);
	}

	$early_action = [];
	$early_action_str = "Не заполнено";
	$res_early_action = $db_connect->query("SELECT request_early_action.id, early_action.name FROM request_early_action JOIN early_action ON early_action.id = request_early_action.early_action_id WHERE request_id = {$reqeust['id']}");					
	while($row_early_action = $res_early_action->fetchAssoc()) {	
		$early_action[] = $row_early_action["name"];
	}
	if(!empty($reqeust["other_early_action"])) {
		$early_action[] = $reqeust["other_early_action"];
	}
	if(count($early_action) !== 0) {
		$early_action_str = implode(", ", $early_action);
	}

	$messengers = [];
	$messengers_str = "Нет";
	$res_messengers = $db_connect->query("SELECT request_messengers.id, messengers.name FROM request_messengers JOIN messengers ON messengers.id = request_messengers.messenger_id WHERE request_id = {$reqeust['id']}");					
	while($row_messengers = $res_messengers->fetchAssoc()) {	
		$messengers[] = $row_messengers["name"];
	}
	if(count($messengers) !== 0) {
		$messengers_str = implode(", ", $messengers);
	}
	if(!empty($reqeust["messenger_phone_number"]) and count($messengers) !== 0) {
		$messengers_str .= ", привязанный номер - " . $reqeust["messenger_phone_number"];
	}

	$client_params = ['debt' => $debt, 'delays' => $delays, 'mortgage' => $mortgage, 'car_loan' => $car_loan, 'movables' => $movables_str, 'early_action' => $early_action_str, 'messengers' => $messengers_str, 'real_estate' => $real_estate_str, 'additional_comment' => $additional_comment, 'audio_link' => $audio_link];

	$res4 = $db_connect->query('SELECT * FROM `st_city_s` WHERE name_city = "' . $reqeust["city"] . '"');
	$city_queryset = $res4->fetchAssoc();

	// exit;
	// if($city_queryset == NULL) {
	// 	http_response_code(404);
	// 	$error = array(
	// 		'error' => 'Не добавлен город и не указана цена заявки!'
	// 	);

	// 	echo json_encode($error);
	// 	exit();
	// }

	$partner_queryset = $db_connect->query('SELECT * FROM `st_partner_s` WHERE id = "' . $partner_id . '"');
	$partner = $partner_queryset->fetchAssoc();
	in_caller($reqeust, $partner, $client_params);


	$res3 = $db_connect->query('SELECT * FROM `balance` WHERE user_id = "' . $partner["user_id"] . '"');
	$balance = $res3->fetchAssoc();

	if(!empty($city_queryset) and !empty($balance)) {
		$res5 = $db_connect->query('
			SELECT * FROM `price` WHERE user_id = "' . $partner["user_id"] . '" AND city_id = "' . $city_queryset["id"] . '"
		');

		$price = $res5->fetchAssoc();

		$bal = $balance["amount"] - $price["amount"];

		$v_sql = 'UPDATE `balance` SET amount = ' . $bal . '  WHERE user_id = "' . $partner["user_id"] . '"';
		$db_connect->query($v_sql);

		$query = "
				INSERT INTO bez_sale_request
					(request_id, partner_id, price, user_id)
				VALUES ({$reqeust["id"]}, {$partner["id"]}, {$price["amount"]}, {$reqeust["user_id"]})
			";

		$db_connect->query($query);
	} else {
		$query = "
			INSERT INTO bez_sale_request
				(request_id, partner_id, price, user_id)
			VALUES ({$reqeust["id"]}, {$partner["id"]}, 0, {$reqeust["user_id"]})
		";

		$db_connect->query($query);
	}

	// $query = "
	// 		INSERT INTO bez_sale_request
	// 			(request_id, partner_id, price)
	// 		VALUES ({$reqeust["id"]}, {$partner["id"]}, 0)
	// 	";

	// $db_connect->query($query);

	$query = "
			UPDATE bez_unprocessed_base
				SET partner = '" . $partner["id"] . "',
				is_ship = 1,
				date_time_status_change = NOW()
			WHERE
				bez_unprocessed_base.id = " . $reqeust['id'] . "
		";

	$db_connect->query($query);

	//для вкладки Партнеры в блоке Продажи

	//для отбраковки
	//находим всех партнеров, у которых есть дата закрытия1 и нет даты закрытия2
	$res_partners_plan1 = $db_connect->query("
		SELECT bez_partners_plan.*
		FROM bez_partners_plan 
		WHERE bez_partners_plan.partner_id = {$partner['id']} AND bez_partners_plan.date_start IS NOT NULL AND bez_partners_plan.date_start <= CURDATE() AND bez_partners_plan.date_end1 IS NOT NULL AND bez_partners_plan.date_end2 IS NULL 
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
		WHERE bez_partners_plan.partner_id = {$partner['id']} AND bez_partners_plan.date_start IS NOT NULL AND bez_partners_plan.date_start <= CURDATE() AND bez_partners_plan.date_end1 IS NULL
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

	$new_data = ['successfully' => 'ok'];

	/*
		$i_text = $i_sql1 . $i_sql2 . $i_sql3 . $i_sql4 . $i_sql5 . $i_sql6 . $i_sql7 . $i_sql8 . $i_sql9 . 'id = "' . $cur_id;
		$i_text = str_replace(',',';',$i_text);
		$i_text = str_replace('"','',$i_text);
		$i_value = 'update';

		$db_connect->query('INSERT INTO logs ( row_change_time, value, text, user_id) VALUES ("' .$i_row_change_time . '", "' .$i_value . '", "' .$i_text . '", "' .$i_user_id . '")');
	*/
	//echo '<pre>'; print_r($_POST); echo '</pre>';
	// header('Location:'. $_SERVER['HTTP_REFERER']);
} else {
	if(empty($plan_check)) {
		$text_warning .= ' его нет в плане!';
	}
	elseif(!$plan_check["limit_count"]) {
		$text_warning .= ' допустимое количество будет превышено!';
	}
	elseif(!$plan_check["time_compliance"]) {
		$text_warning .= ' текущее время не соответствует времени отгрузки!';
	}
    $new_data = ['warning' => $text_warning];
}

http_response_code(200);
header('Content-Type: application/json');
echo json_encode($new_data);  


?>