<?

writeToLog($_REQUEST, 'lead', $id);






function writeToLog($data, $title = '', $id) {
    $log = "\n------------------------\n";
    $log .= date("Y.m.d G:i:s") . "\n";
    $log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
    $log .= print_r($data, 1);
	$log .= print_r($id, 1);
    $log .= "\n------------------------\n";
    file_put_contents(getcwd() . '/hookin.log', $log, FILE_APPEND);
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