<?


if ($_SERVER["HTTP_AUTHORIZATION"] == 'Bearer eyJhbGfci4OiJSUzI35NiIsd3ImtpZCI5IjI32Y' ) {
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';
	
	$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);
	
			$db_connect->query('INSERT INTO logs (row_change_time, value, modul, text, user_id )
								   VALUES( NOW(),
								   		   "insert",
										   "atc_call_scor",
										   "data = ' . $data . '",
										   "999"
											)');		

			$db_connect->query('INSERT INTO logs (row_change_time, value, modul, text, user_id )
								   VALUES( NOW(),
								   		   "insert",
										   "atc_call_scor",
										   "postData = ' . $postData . '",
										   "999"
											)');				
			$db_connect->query('INSERT INTO logs (row_change_time, value, modul, text, user_id )
								   VALUES( NOW(),
								   		   "insert",
										   "atc_call_scor",
										   "data_type = ' . $data['type'] . '",
										   "999"
											)');		
			$db_connect->query('INSERT INTO logs (row_change_time, value, modul, text, user_id )
								   VALUES( NOW(),
								   		   "insert",
										   "atc_call_scor",
										   "data_type = ' . $data['lead'] . '",
										   "999"
											)');
// Извлекаем данные по разделам
$lead = $data['lead'];
$contact = $data['contact'];
$call = $data['call'];
$callResult = $data['call_result'];

// Пример обработки данных лида
$leadId = (int)$lead['id'];
$leadName = $lead['name'] ?? '';
$leadPhone = $lead['phones'] ?? '';
$leadEmail = $lead['emails'][0] ?? null;

// Пример обработки данных контакта
$contactId = (int)$contact['id'];
$contactName = $contact['name'] ?? '';
$contactPhone = $contact['phones'] ?? '';

// Пример обработки данных звонка
$callId = (int)$call['id'];
$callPhone = $call['phone'] ?? '';
$callSource = $call['source'] ?? '';
$callDirection = $call['direction'] ?? '';
$callStartedAt = $call['started_at'] ?? '';
$callDuration = (int)$call['duration'];
$callRecordingUrl = $call['recording_url'] ?? '';
$calluser_id = $call['user_id'] ?? '';

// Пример обработки результата звонка
$resultId = (int)$callResult['result_id'];
$resultName = $callResult['result_name'] ?? '';
$resultComment = $callResult['comment'] ?? '';

			$db_connect->query('INSERT INTO logs (row_change_time, value, modul, text, user_id )
								   VALUES( NOW(),
								   		   "insert",
										   "atc_call_scor",
										   "all = ' . $leadId . '","' . 
										   $leadName . '","' . 
										   $leadPhone . '","' . 
										   $leadEmail . '","' . 
										   $contactId . '","' . 
										   $contactName . '","' . 
										   $contactPhone . '","' . 
										   $callId . '","' . 
										   $callPhone . '","' . 
										   $callSource . '","' . 
										   $callDirection . '","' . 
										   $callStartedAt . '","' . 
										   $callDuration . '","' . 
										   $callRecordingUrl . '","' . 
										   $calluser_id . '","' . 
										   $resultId . '","' . 
										   $resultName . '","' . 
										   $resultComment . '" ,
										   "999"
											)');
											
/*
	if ($data["number"] <> '' and $data["agent"] <> '') {
		$db_connect->query('INSERT INTO atc_call_log ( phone_number, agent_id, is_call, method_call)
							   VALUES( "' . $data["number"] . '",
									   "' . $data["agent"] . '",
									   1,
									   "AO"
										)');
		if(DEBUG == 'y') {
			$db_connect->query('INSERT INTO logs (row_change_time, value, modul, text, user_id )
								   VALUES( NOW(),
								   		   "insert",
										   "atc_call_scor",
										   "phone_number = ' . $data["number"] . '",
										   "' . $data["agent"] . '"
											)');			
		}

	} else {
		$db_connect->query('INSERT INTO logs ( value, modul, text )
							   VALUES( "insert",
									   "atc_call_log",
									   "' . $data . '"
										)');		
	}
*/
}
if ($_SERVER["HTTP_AUTHORIZATION"] == 'Bearer eyJhbGciOiJSUzI3NiIsImtpZCI5IjI2Y' ) {
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';
	
	$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);
/*
	if ($data["number"] <> '' and $data["agent"] <> '') {
		$db_connect->query('INSERT INTO atc_call_log ( phone_number, agent_id, is_call, method_call)
							   VALUES( "' . $data["number"] . '",
									   "' . $data["agent"] . '",
									   1,
									   "AO"
										)');
		if(DEBUG == 'y') {
			$db_connect->query('INSERT INTO logs (row_change_time, value, modul, text, user_id )
								   VALUES( NOW(),
								   		   "insert",
										   "atc_call",
										   "phone_number = ' . $data["number"] . '",
										   "' . $data["agent"] . '"
											)');			
		}

	} else {
		$db_connect->query('INSERT INTO logs ( value, modul, text )
							   VALUES( "insert",
									   "atc_call_log",
									   "' . $data . '"
										)');		
	}
*/
}
if ($_SERVER["HTTP_AUTHORIZATION"] == 'Bearer eyJhbGciOiJSUzI3NiIsImtpZCsdfgI5IjI2Y' ) {
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';
	
	$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);
/*
	if ($data["number"] <> '' and $data["agent"] <> '') {
		$db_connect->query('INSERT INTO atc_call_log ( phone_number, agent_id, is_call, method_call)
							   VALUES( "' . $data["number"] . '",
									   "' . $data["agent"] . '",
									   1,
									   "AO"
										)');
		if(DEBUG == 'y') {
			$db_connect->query('INSERT INTO logs (row_change_time, value, modul, text, user_id )
								   VALUES( NOW(),
								   		   "insert",
										   "atc_call",
										   "phone_number = ' . $data["number"] . '",
										   "' . $data["agent"] . '"
											)');			
		}

	} else {
		$db_connect->query('INSERT INTO logs ( value, modul, text )
							   VALUES( "insert",
									   "atc_call_log",
									   "' . $data . '"
										)');		
	}
*/
}

if ($_SERVER["HTTP_AUTHORIZATION"] == 'Bearer zG529qEEBz5K92JrvgYQrBxIXYGix3QM' ) {

	/*

   	"id": "7060324",  //id строки в таблице app_entity_25
    "number": "79028242599", //номер телефона по которому завершен звонок
    "status": "call_end",  // завершенный звонок
    "dualstatus": "BUSY" // статус звонка

    данные запросы настроил на отправку с ручного исходящего набора и автообзвона, статусы которых не CHANUNAVAIL и CONGESTION

	*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';
	
	$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);
	$phone_number = substr($data["number"], -10);

	if ($phone_number <> '' and $data["status"] == 'call_end') {
		$db_connect->query("INSERT INTO touches_phone_number (phone_number, count_touches) 
							VALUES ({$phone_number}, 1) 
							ON DUPLICATE KEY UPDATE 
							    count_touches = count_touches + 1;");

	} else {
		$db_connect->query('INSERT INTO logs ( value, modul, text )
							   VALUES( "insert",
									   "atc_call_log",
									   "' . $data . '"
										)');		
	}

}


?>
