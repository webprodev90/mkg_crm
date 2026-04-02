<?php

require_once dirname(__FILE__) . '/access.php';

$names = 'Иванов Иван';
$name = 'Новая сделка МКГ';
$phone = '+79999999999';
$email = 'email@gmail.com';
$target = 'Цель';
$company = 'Название компании';
$mass = 'Сообщение';

$custom_field_id = 907617;
$custom_field_value = 'тест';

$ip = '1.2.3.4';
$domain = 'site.ua';
$price = 10;
$pipeline_id = 7604466;
$user_amo = 31469878;

$utm_source   = '1';
$utm_content  = '2';
$utm_medium   = '3';
$utm_campaign = '4';
$utm_term     = '5';
$utm_referrer = '6';

$leads['request']['leads']['add']=array(
	array(
		'name' => $name,
		'status_id' => '1', //id статуса
		'responsible_user_id' => $user_amo, //id ответственного по сделке
		//'date_create'=>1298904164, //optional
		//'price'=>300000,
		//'tags' => 'Important, USA', #Теги
		'custom_fields'=>array( 
			array( 'id' => 941765, 
				   'values' => array( 
							   array( 'value' => $phone ) ) ),
			array( 'id' => 941767, 
				   'values' => array( 
							   array( 'value' => $mass ) ) ),							   
			array( 'id' => 907617, 
			       'values' => array( 
				               array( 'value' => $names ) ) ) 
			)
	)
);

$method = "/private/api/v2/json/leads/set";

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token,
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
curl_setopt($curl, CURLOPT_URL, "https://$subdomain.amocrm.ru".$method);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($leads));
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_COOKIEFILE, 'amo/cookie.txt');
curl_setopt($curl, CURLOPT_COOKIEJAR, 'amo/cookie.txt');
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
$out = curl_exec($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$code = (int) $code;
$errors = [
    301 => 'Moved permanently.',
    400 => 'Wrong structure of the array of transmitted data, or invalid identifiers of custom fields.',
    401 => 'Not Authorized. There is no account information on the server. You need to make a request to another server on the transmitted IP.',
    403 => 'The account is blocked, for repeatedly exceeding the number of requests per second.',
    404 => 'Not found.',
    500 => 'Internal server error.',
    502 => 'Bad gateway.',
    503 => 'Service unavailable.'
];

if ($code < 200 || $code > 204) die( "Error $code. " . (isset($errors[$code]) ? $errors[$code] : 'Undefined error') );

$Response=json_decode($out,true);
//echo '<b>Новая сделка:</b>'; echo '<pre>'; print_r($Response); echo '</pre>';
if(is_array($Response['response']['leads']['add']))
	foreach($Response['response']['leads']['add'] as $lead) {
		$lead_id = $lead["id"]; //id новой сделки
	};
curl_close($curl);
/*
$Response = json_decode($out, true);
$Response = $Response['add'][0]['_embedded']['items'];
$output = 'ID добавленных элементов списков:' . PHP_EOL;
foreach ($Response as $v)
    if (is_array($v))
        $output .= $v['id'] . PHP_EOL;
return $output ;*/

//ДОБАВЛЕНИЕ КОНТАКТА
$contact = array(
	'name' => 'МКГ - ' . $names,
	'linked_leads_id' => array($lead_id), //id сделки
	'responsible_user_id' => $user_amo, //id ответственного
	'custom_fields'=>array(
		array(
			'id' => 747923,
			'values' => array(
				array(
					'value' => $phone,
					'enum' => 'MOB'
				)
			)
		)
	)
);

$set['request']['contacts']['add'][]=$contact;

$method2 = "/private/api/v2/json/contacts/set";

$headers2 = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token,
];

$curl2 = curl_init();
curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl2, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
curl_setopt($curl2, CURLOPT_URL, "https://$subdomain.amocrm.ru".$method2);
curl_setopt($curl2, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl2, CURLOPT_POSTFIELDS, json_encode($set));
curl_setopt($curl2, CURLOPT_HTTPHEADER, $headers2);
curl_setopt($curl2, CURLOPT_HEADER, false);
curl_setopt($curl2, CURLOPT_COOKIEFILE, 'amo/cookie.txt');
curl_setopt($curl2, CURLOPT_COOKIEJAR, 'amo/cookie.txt');
curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl2, CURLOPT_SSL_VERIFYHOST, 0);
$out2 = curl_exec($curl2);
$code2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
$code2 = (int) $code2;
$errors2 = [
    301 => 'Moved permanently.',
    400 => 'Wrong structure of the array of transmitted data, or invalid identifiers of custom fields.',
    401 => 'Not Authorized. There is no account information on the server. You need to make a request to another server on the transmitted IP.',
    403 => 'The account is blocked, for repeatedly exceeding the number of requests per second.',
    404 => 'Not found.',
    500 => 'Internal server error.',
    502 => 'Bad gateway.',
    503 => 'Service unavailable.'
];
curl_close($curl2);
if ($code2 < 200 || $code2 > 204) die( "Error $code2. " . (isset($errors2[$code2]) ? $errors2[$code2] : 'Undefined error') );

