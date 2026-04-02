<?
if ($_REQUEST['auth']['application_token'] == '6ffsfkofrtwob1habiyncent7khrwux7') {

	$id = $_REQUEST['data']['FIELDS']['ID'];
	
	$queryURL = "https://b24-478tqu.bitrix24.ru/rest/22/b7yeze2b9sxrmb88/crm.deal.get.json";

	$queryData = http_build_query(
		array(
			"ID" => $id
		)
	);

	$id_lead = out_bitrix24($queryURL, $queryData);

	if ($id_lead['STAGE_ID'] == 'FINAL_INVOICE' and $id_lead['SOURCE_ID'] == 'UC_WBH5YJ') {

		$queryURL2 = "https://b24-478tqu.bitrix24.ru/rest/22/d1klz656uu3zfill/crm.contact.get.json";

		$queryData2 = http_build_query(
			array(
				"ID" => $id_lead['CONTACT_ID']
			)
		);

		$contact_arr = out_bitrix24($queryURL2, $queryData2);

		$queryURL3 = "https://marker.bitrix24.ru/rest/37371/cyc7iidwys9ub3jd/crm.lead.add.json";
	
		// формируем параметры для создания лида
		$queryData3 = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $id_lead["TITLE"],
					"NAME" => $contact_arr['NAME'], 
					"COMMENTS" => $id_lead['COMMENTS'],
					"UTM_SOURCE" => "mkg", 
					"UF_CRM_1545816243" => $id_lead["UF_CRM_1739456948938"],
					"UF_CRM_1739461608" => $id,
					"PHONE" => array(	
						"n0" => array(
							"VALUE" => $contact_arr['PHONE'][0]['VALUE'],	
							"VALUE_TYPE" => "WORK",		
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	
			)
		);
		
		out_bitrix24($queryURL3, $queryData3);
		writeToLog($id_lead, 'lead', $id);
 
	}
	
}




function writeToLog($data, $title = '', $id) {
    $log = "\n------------------------\n";
    $log .= date("Y.m.d G:i:s") . "\n";
    $log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
    $log .= print_r($data, 1);
	$log .= print_r($id, 1);
    $log .= "\n------------------------\n";
    file_put_contents(getcwd() . '/hook.log', $log, FILE_APPEND);
    return true;
}
function out_bitrix24($queryURL, $queryData) {
	// отправляем запрос в Б24 и обрабатываем ответ
	$curl = curl_init();
	curl_setopt_array(
		$curl,
		array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $queryURL,
			CURLOPT_POSTFIELDS => $queryData,
		)
	);
	$result = curl_exec($curl);
	curl_close($curl);
	$result = json_decode($result, 1);

	if(isset($result)) {
		// если произошла какая-то ошибка - выведем её
		if(array_key_exists('error', $result)) {
			die("Ошибка при сохранении лида: " . $result['error_description']);
		}

		if(array_key_exists('result', $result)) {
			return $result['result'];
		}	
	}	
}