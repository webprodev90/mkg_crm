<?php
/*
	 //Ключ защиты
	 if(!defined('BEZ_KEY'))
	 {
		 header("HTTP/1.1 404 Not Found");
		 exit(file_get_contents('/404.html'));
	 }
*/


	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	 
	require_once BEZ_DOCR . '/pmap/PHPMailer/src/Exception.php';
	require_once BEZ_DOCR . '/pmap/PHPMailer/src/PHPMailer.php';
	require_once BEZ_DOCR . '/pmap/PHPMailer/src/SMTP.php';
	//require_once '/var/www/u375143/data/www/crm.mkggroup.ru/inc/config.php';
	require_once BEZ_DOCR . '/inc/bd/bd.php';		

	

/* Функция чистого номера
phone_number - номер телефона
cod - первые цифры номера, например +7,8,7....
 */
function clean_phone_number_cod($phone_number, $cod) {
	$cleaned_phone_number = preg_replace('/\D/', '', $phone_number);

	if(preg_match('/^[87]/', $cleaned_phone_number)) {
		$cleaned_phone_number = substr($cleaned_phone_number, 1);
	}
	
	$cleaned_phone_number = substr($cleaned_phone_number, -10);
		
	return $cod . $cleaned_phone_number;
}

/**Функция экранирования вносимых данных
 *@param array $data
 */
function escape_str($data) {
	if(is_array($data)) {
		if(get_magic_quotes_gpc())
			$strip_data = array_map("stripslashes", $data);
		$result = array_map("mysql_real_escape_string", $strip_data);
		return $result;
	} else {
		if(get_magic_quotes_gpc())
			$data = stripslashes($data);
		$result = mysql_real_escape_string($data);
		return $result;
	}
}

/**Отпровляем сообщение на почту c почтового сервера
 * @param string  $to
 * @param string  $from
 * @param string  $title
 * @param string  $message
 */

 //SMTP smtp.bz
 function sendMessageMail5($to, $from, $title, $message) {

	$mail = new PHPMailer();
	$mail->CharSet = 'UTF-8';

	$yourEmail = 'webprodev@yandex.ru'; // ваш email 
	$password = 'G19PUnJ4NLMd'; //  ваш пароль
	$froms = 'info@crm.mkggroup.ru'; //Отправитель
	// Настройки SMTP

	//$mail->SMTPDebug = 3; 
    $mail->isSMTP();
	
	$mail->Host = 'connect.smtp.bz';
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;

    $mail->SMTPAuth   = true;
	$mail->Username = $yourEmail; // ваш email - тот же что и в поле From:
	$mail->Password = $password; // ваш пароль;




	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);

	// формируем письмо

	// от кого
	$mail->setFrom($froms, 'МКГ');

	// кому - получатель письма
	$mail->addAddress($to, $to);  // кому

	$mail->Subject = $title;  // тема письма

	$mail->msgHTML($message);


	if ($mail->send()) { // отправляем письмо
		return true;
	} else {
		return 'Ошибка: ' . $mail->ErrorInfo;
	}

		
}
 //SMTP mail.ru
 function sendMessageMail2($to, $from, $title, $message) {

	$mail = new PHPMailer();
	$mail->CharSet = 'UTF-8';

	$yourEmail = 'ivan.kholostov.91@mail.ru'; // ваш email на яндексе
	$password = 'kgdHVMUpxdQAnvsRiA7W'; //  ваш пароль к яндексу или пароль приложения

	// Настройки SMTP
	$mail->isSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPDebug = 0;

	$mail->Mailer = 'smtp';
	$mail->Host = 'ssl://smtp.mail.ru';
	$mail->Port = 465;
	$mail->Username = $yourEmail; // ваш email - тот же что и в поле From:
	$mail->Password = $password; // ваш пароль;

	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);

	// формируем письмо 

	// от кого: это поле должно быть равно вашему email иначе будет ошибка
	$mail->setFrom($yourEmail, 'МКГ');

	// кому - получатель письма
	$mail->addAddress($to, $to);  // кому

	$mail->Subject = $title;  // тема письма

	$mail->msgHTML($message);


	if ($mail->send()) { // отправляем письмо
		return true;
	} else {
		return 'Ошибка: ' . $mail->ErrorInfo;
	}

		
}
function sendMessageMail($to, $from, $title, $message) {

	$mail = new PHPMailer();
	$mail->CharSet = 'UTF-8';

	$yourEmail = 'mgk.gs2@yandex.ru'; // ваш email на яндексе
	$password = 'kehmpkczemhocviu'; // SXNnV93Ru2kUGmLbX3dN ваш пароль к яндексу или пароль приложения

	// Настройки SMTP
	$mail->isSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPDebug = 0;

	$mail->Mailer = 'smtp';
	$mail->Host = 'ssl://smtp.yandex.ru';
	$mail->Port = 465;
	$mail->Username = $yourEmail; // ваш email - тот же что и в поле From:
	$mail->Password = $password; // ваш пароль;

	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);

	// формируем письмо

	// от кого: это поле должно быть равно вашему email иначе будет ошибка
	$mail->setFrom($yourEmail, 'МКГ');

	// кому - получатель письма
	$mail->addAddress($to, $to);  // кому

	$mail->Subject = $title;  // тема письма
	$mail->msgHTML($message);


	if ($mail->send()) { // отправляем письмо
		return true;
	} else {
		return 'Ошибка: ' . $mail->ErrorInfo;
	}

		
}
/**Отпровляем сообщение на почту
 * @param string  $to
 * @param string  $from
 * @param string  $title
 * @param string  $message
 */
function sendMessageMail3($to, $from, $title, $message) {
	//Адресат с отправителем
	//$to = $to;
	//$from = $from;

	//Формируем заголовок письма
	$subject = $title;
	$subject = '=?utf-8?b?' . base64_encode($subject) . '?=';

	//Формируем заголовки для почтового сервера
	$headers = "Content-type: text/html; charset=\"utf-8\"\r\n";
	$headers .= "From: " . $from . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Date: " . date('D, d M Y h:i:s O') . "\r\n";

	//Отправляем данные на ящик админа сайта
	if(!mail($to, $subject, $message, $headers))
		return 'Ошибка отправки письма!';
	else
		return true;
}
/*
function fromMailPhone($phone_number) {
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';	
	
	// логин
	$email = "dev@crm.mkggroup.ru";
	// пароль 
	$password = "qS7mP3vX9p";
	// папка
	$folder = imap_utf8_to_mutf7('mbd');
	// соединяемся с почтовым сервером, 
	// в случае ошибки выведем ее на экран
	

	$connect_imap = imap_open("{s132.webhost1.ru:993/imap/ssl}". $folder, $email, $password) or die("Error:" . imap_last_error());

	// проверим ящик на наличие новых писем
	$mails = imap_search($connect_imap, 'ALL');
	// если есть новые письма
	
		// перебираем все письма
		foreach($mails as $num_mail){
			// получаем заголовок
			$header = imap_headerinfo($connect_imap, $num_mail);
			// достаем ящик отправителя письма
			$mail_from = $header->sender[0]->mailbox . "@" . $header->sender[0]->host;
			// получаем тему письма
			$subject = iconv_mime_decode($header->subject,0,"UTF-8");
			// получаем содержимое письма
			$text_mail = imap_fetchbody($connect_imap, $num_mail, 1);
			
			$text_mail = str_replace('Телефон: ', '', $text_mail);
			$phone_number = substr($phone_number, -10);
			$pos = strpos($text_mail, $phone_number);
			
			if ($pos == '1') {
	
				$res = $db_connect->query('SELECT count(*) as rc FROM `bez_unprocessed_base` WHERE phone_number = ' . $phone_number );					
				while( $row = $res->fetchAssoc() ){	
				    if ($row["rc"] > 0) {
						$db_connect->query('update `bez_unprocessed_base` set is_double = 1 WHERE phone_number = ' . $phone_number );
					}
				}	
			}

		}
			
	// закрываем соединение
	imap_close($connect_imap);
}
*/
 function sendMessageMail4($to, $from, $title, $message) {

	$mail = new PHPMailer();
	$mail->CharSet = 'UTF-8';

	$yourEmail = 'mgk.gs@rambler.ru'; // ваш email на яндексе
	$password = 'CRMmkggroup9'; //  ваш пароль к яндексу или пароль приложения

	// Настройки SMTP
	$mail->isSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPDebug = 0;

	$mail->Mailer = 'smtp';
	$mail->Host = 'ssl://smtp.rambler.ru';
	$mail->Port = 465;
	$mail->Username = $yourEmail; // ваш email - тот же что и в поле From:
	$mail->Password = $password; // ваш пароль;

	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);

	// формируем письмо

	// от кого: это поле должно быть равно вашему email иначе будет ошибка
	$mail->setFrom($yourEmail, 'МКГ');

	// кому - получатель письма
	$mail->addAddress($to, $to);  // кому

	$mail->Subject = $title;  // тема письма

	$mail->msgHTML($message);


	if ($mail->send()) { // отправляем письмо
		return true;
	} else {
		return 'Ошибка: ' . $mail->ErrorInfo;
	}

		
}

/**функция вывода ошибок
 * @param array  $data
 */
function showErrorMessage($data) {
	$err = '<ul>' . "\n";

	if(is_array($data)) {
		foreach($data as $val)
			$err .= '<li style="color:red;">' . $val . '</li>' . "\n";
	} else
		$err .= '<li style="color:red;">' . $data . '</li>' . "\n";

	$err .= '</ul>' . "\n";

	return $err;
}
function showError($data) {
	$_SESSION['err'] = $data;
}

/**Простая обертка для запросов к MySQL
 * @param string  $sql
 */
function mysqlQuery($sql) {
	$res = mysql_query($sql);
	/* Проверяем результат
							   Это показывает реальный запрос, посланный к MySQL, а также ошибку. Удобно при отладке.*/
	if(!$res) {
		$message = 'Неверный запрос: ' . mysql_error() . "\n";
		$message .= 'Запрос целиком: ' . $sql;
		die($message);
	}

	return $res;
}

/**Простой генератор соли
 * @param string  $sql
 */
function salt() {
	$salt = substr(md5(uniqid()), -8);
	return $salt;
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

/*
Транслит
change_case - к какому регистру приводить: L - к нижнему, U - к верхнему, N - не изменять. По умолчанию - "L"
i_space - чем заменять пробел, по умолчанию: "_"
*/
function translit($value, $i_space = '_', $change_case = 'L',)
{
	
	$converter = array(
		'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
		'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
		'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
		'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
		'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
		'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
		'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
 
		'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
		'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
		'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
		'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
		'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
		'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
		'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
	);
 
	$value = strtr($value, $converter);
	$value = preg_replace("/\s/", $i_space, $value);
	
	if ($change_case == 'U') {
		$value = mb_strtoupper($value);
	} else if ($change_case == 'L') {
		$value = mb_strtolower($value);
	}	
	
	
	
	return $value;
}
/* Генерация паролей */
function generator_password($length = 8)
{				
	$chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP'; 
	$size = strlen($chars) - 1; 
	$password = ''; 
	while($length--) {
		$password .= $chars[random_int(0, $size)]; 
	}
	return $password;
}

function sendMessageTelegram($tg_token, $tg_chatid, $row, $partner_id = '0') {
	// сюда нужно вписать токен вашего бота ponomarevmkg
	define('TELEGRAM_TOKEN', $tg_token);
		// сюда нужно вписать ваш внутренний айдишник
	define('TELEGRAM_CHATID', $tg_chatid);

	if($partner_id == '382') {
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '+7');
	} elseif($partner_id == '497') {
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '8');
	} else {
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
	}
		
	function message_to_telegram($text) {
		$ch = curl_init();
		curl_setopt_array(
			$ch,
			array(
				CURLOPT_URL => 'https://api.telegram.org/bot' . TELEGRAM_TOKEN . '/sendMessage',
				CURLOPT_POST => TRUE,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_TIMEOUT => 10,
				CURLOPT_POSTFIELDS => array(
					'chat_id' => TELEGRAM_CHATID,
					'text' => $text,
				),
			)
		);
		curl_exec($ch);
	}		
		
		// текст отправки
	$v_txt = trim($row["fio"]) . "\n" . trim($row["phone_number"]) . "\n" . trim($row["city"]) . "\n" . trim($row["vopros"]);

	message_to_telegram($v_txt);	
}

function in_caller($row, $row2, $row3) {

	if($row["fio"]) {
		$i_mt1 = 'ФИО: ' . $row["fio"] . '<br> ';
	}
	if($row["phone_number"]) {
		$i_mt2 = 'Телефон: ' . $row["phone_number"] . '<br> ';
	}
	if($row["vopros"]) {
		$i_mt3 = 'Вопрос: ' . $row["vopros"] . '<br> ';
	}
	if($row["city"]) {
		$i_mt4 = 'Город: ' . $row["city"] . '<br> ';
	}

/*
		$to = 'webprodev@yandex.ru'; //Адресат 
		$from = 'info@mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		
		$i_mt5 = ', is_audio: ' . $row["is_audio"];
		$i_mt6 = ', link: ' . $row["link"];
		$i_mt7 = ', id: ' . $row2["id"];
		
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6 . $i_mt7;
		sendMessageMail5($to, $from, $title, $message);	
*/
	// Добавить лид в личный кабинет партнера
/*	$ch = curl_init();

	$queryURL = 'https://lk.mkggroup.ru/admin/api/rest.php'
		. '?param=addlead'
		. '&key=fa9sgwl456gjs9g456dsjlgjd456sjg45345789lsdlgs'
		. '&name=' . curl_escape($ch, trim($row["fio"]))
		. '&phone=' . curl_escape($ch, trim($row["phone_number"]))
		. '&city=' . curl_escape($ch, trim($row["city"]))
		. '&on_date=' . curl_escape($ch, date("Y-m-d H:i:s"))
		. '&partner_id=' . curl_escape($ch, trim($row2["id"]))
		. '&question=' . curl_escape($ch, trim($row["vopros"]));

	curl_setopt($ch, CURLOPT_URL, $queryURL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);*/


	if($row2["id"] == '4') {

		$queryURL = "https://bankrot40.bitrix24.ru/rest/385/yw2926bxvhufeowf/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "229",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

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

		// если произошла какая-то ошибка - выведем её
		if(array_key_exists('error', $result)) {
			die("Ошибка при сохранении лида: " . $result['error_description']);
		}

		$to = $row2["email"]; //Адресат
		//$to = 'bratva1990@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Лид с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '242') {
		
	} elseif($row2["id"] == '6') {

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm ' . $row["phone_number"];
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);

	} elseif($row2["id"] == '7') {
		$queryURL = "https://garantiya-ru.bitrix24.ru/rest/7/3n6ou01mmkhak313/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					//"SOURCE_ID" => "229",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

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

		// если произошла какая-то ошибка - выведем её
		if(array_key_exists('error', $result)) {
			die("Ошибка при сохранении лида: " . $result['error_description']);
		}

	} elseif($row2["id"] == '8') {
		$queryURL = "https://b24-szuyl5.bitrix24.ru/rest/1/3n74jpk1dylha9ux/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					//"SOURCE_ID" => "229",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

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

		// если произошла какая-то ошибка - выведем её
		if(array_key_exists('error', $result)) {
			die("Ошибка при сохранении лида: " . $result['error_description']);
		}

	} elseif($row2["id"] == '9') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.МСК';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '11') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.СПБ';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '15') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		//$to = $row2["email"]; //Адресат
		$to = 'alleadserv@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Краснодар';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '17') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Тагил';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '19') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Кемерово';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '20') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Тюмень';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '21') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Ставрополь';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '22') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Ростов';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '24') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Новосибирск';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '25') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Красноярск';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '26') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Ярославль';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '27') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Волгоград';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '59') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.РФ';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '28') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Тула';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '10') {
		$queryURL = "https://ius.bitrix24.ru/rest/96/ergh3cxoht248hx1/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "m.id",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '12') {

		$ch = curl_init();

		$city = trim($row["city"]);
		$name = trim($row["fio"]);
		$phone = trim($row["phone_number"]);

		$queryURL = 'https://m-id.group/api/kirov/forward'
			. '?web=mid'
			. '&NAME=' . curl_escape($ch, $name)
			. '&PHONE=' . curl_escape($ch, $phone)
			. '&UF_CRM_1648894308=' . curl_escape($ch, $city)
			. '&SOURCE_DESCRIPTION=MID_Мережко'
			. '&COMMENTS=' . curl_escape($ch, $row["vopros"]);

		curl_setopt($ch, CURLOPT_URL, $queryURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
	} elseif($row2["id"] == '30') {

		$ch = curl_init();

		$city = trim($row["city"]);
		$name = trim($row["fio"]);
		$phone = trim($row["phone_number"]);

		$queryURL = 'https://crm.fcb.expert/local/webhook/lead-add-test/index.php'
			. '?web=mkg'
			. '&NAME=' . curl_escape($ch, trim($row["fio"]))
			. '&PHONE=' . curl_escape($ch, trim($row["phone_number"]))
			. '&CITY=' . curl_escape($ch, $city)
			//. '&UF_CRM_1648894308=' . curl_escape($ch, $city)
			//. '&SOURCE_DESCRIPTION=MID_Мережко'
			. '&COMMENTS=' . curl_escape($ch, trim($row["vopros"]));

		curl_setopt($ch, CURLOPT_URL, $queryURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
	} elseif($row2["id"] == '31') {
		$queryURL = "https://onlinebankrotstvo.bitrix24.ru/rest/8515/2u5a8hi52ad1mcpk/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "23",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '32') {
		$queryURL = "https://bankrotprosto.bitrix24.ru/rest/3254/7xoit0j2f7f01uup/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm. Екатеринбург",
					"NAME" => $row["fio"],
					"COMMENTS" => 'Шонов_Екатеринбург. ' . $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "Shonov",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '33') {
		$queryURL = "https://iburnatsev.bitrix24.ru/rest/1/c22dkgjn70jkgdqs/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm. Ставрополь",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "barencev",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif(
		$row2["id"] == '34' || 
		$row2["id"] == '50' ||
		$row2["id"] == '51' ||
		$row2["id"] == '52' ||
		$row2["id"] == '53' ||
		$row2["id"] == '54' ||
		$row2["id"] == '103'
	) {
		$queryURL = "https://marker.bitrix24.ru/rest/37371/jr00mjtl5ad2dmhd/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "mkg",
					"UTM_SOURCE" => "mkg",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '35') {
		$queryURL = "https://pelzar.tmweb.ru/rest/17/v8pojn6jmt4v6vrf/crm.lead.add";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "mkg",
					"UTM_SOURCE" => "mkg",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '37') {
		$queryURL = "https://h.albato.ru/wh/38/1lfof9k/AB-PhTWDfZVmSR-TGotB8p6vIuQ3xVVzAI8-tssU190/";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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

	} elseif($row2["id"] == '38') {
		$queryURL = "https://webjack.ru/webhooks/http/ce461f1fa6534fe2bbf108d142ed073a/";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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

	} elseif($row2["id"] == '39') {
		$queryURL = "https://tarangroup.bitrix24.ru/rest/26/mvf3236bjxkdk2br/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "30",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '40') {
		$queryURL = "https://line-prava.pro/rest/36974/ggrgbra184zijau1/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "9679082891",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '41') {
		$queryURL = "https://braincon.bitrix24.ru/rest/1342/275d1k8u889oy3p5/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_XMWYIF",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '42') {
		$queryURL = "https://yurekspert.bitrix24.ru/rest/541/dowbrvcchjmrsx1r/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "1",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);


		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '44') {
		$queryURL = "https://bankrot40.bitrix24.ru/rest/385/xrpxokzmjkmj340j/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "284",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);


		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '45') {
		//$queryURL = "https://yuktrida.bitrix24.ru/rest/1/5no2fiyd81tlb2f0/crm.lead.add.json";
		//$queryURL = "https://kozhenkovamv5.envycrm.com/openapi/v1/lead/set/?api_key=a6800cfec239a7026c296f9e2aa338dabcb89aec";

		$link = 'https://kozhenkovamv5.envycrm.com/crm/api/v1/lead/set/?api_key=a6800cfec239a7026c296f9e2aa338dabcb89aec';
		$curl = curl_init();

		$data = [
			'method' => 'create', // метод, 'create' - для создания, 'update' - для обновления, в данном случае использовать нет необходимости
			'inbox_type_id' => 1333339, // id типа входящего обращения
			'values' => [ // массив значений системных и произвольных полей
				'name' => $row["fio"], // имя
				'phone' => $row["phone_number"], // телефон
				'email' => "", // email
				'comment' => $row["city"] . '. ' . $row["vopros"],
				'utm_source' => "МКГ"
			]
		];

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, $link);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(['request' => $data]));
		curl_setopt($curl, CURLOPT_HEADER, false);

		$out = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);


	} elseif($row2["id"] == '48') {
		
		$queryURL = "https://yur-konsalt.bitrix24.ru/rest/4006/d2x7lwrxf3mj0kji/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "29", 
					"UTM_SOURCE" => "ИП Григоренко",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '49') {
		$queryURL = "https://tsentrspisaniyadolgov.bitrix24.ru/rest/80/z8f8hirce5rmr7oz/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_X5KRYH",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);


		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '55') {

		sendMessageTelegram('6536859946:AAGax8nZ2IJM0XCgpm_4HEzBzgNFSQKg_Uc', '-1002003754377', $row);

	} elseif($row2["id"] == '56') {

		$queryURL = "https://1pravo.bitrix24.ru/rest/16646/3o9o113cakj4f626/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "39",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);


		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '49') {
		$queryURL = "https://tsentrspisaniyadolgov.bitrix24.ru/rest/80/z8f8hirce5rmr7oz/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_X5KRYH",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '57') {

		// сюда нужно вписать токен вашего бота
		//define('TELEGRAM_TOKEN', '6916674276:AAHsvvz4mRLyHHE4BY-lTPmT0OlHjnVvjpA');
		// сюда нужно вписать ваш внутренний айдишник
		//define('TELEGRAM_CHATID', '-1002131040231');
		$queryURL = "https://raskreditaciya.bitrix24.ru/rest/1160/6wh5paepp8nmjsi6/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "42",
					"UF_CRM_1701762695" => $row["city"],
					"UF_CRM_1706180823" => $row["fio"],
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '58') {

		sendMessageTelegram('6905228169:AAEAqnF840n8VG_fEoHlj_IxiNNgzJTnvvs', '-1002129995835', $row);

	} elseif($row2["id"] == '60' || $row2["id"] == '61' || $row2["id"] == '62' || $row2["id"] == '66' || $row2["id"] == '160' || $row2["id"] == '161' || $row2["id"] == '183' || $row2["id"] == '184') {
		$queryURL = "https://bitrix.doverie2007.ru/rest/10/izy04zdz98xas4o0/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "79655339515",
					"UTM_SOURCE" => "mkg",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '63') {

		sendMessageTelegram('6686850912:AAFrqVJVcG50p9rc6sQc_SWcH8QEGcLdf7M', '-1002038403241', $row);

	} elseif($row2["id"] == '64') {
		$queryURL = "https://nashepravo.bitrix24.ru/rest/36/xaugh4n1pmvqnfev/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "19",
					"UTM_SOURCE" => "mkg",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '65') {
		//Брак, ничего не делаем

	} elseif($row2["id"] == '67') {

		sendMessageTelegram('6756503835:AAEmf7bL_2kVa825gNW1PFBDFIFsLUbp3z0', '-1002068112840', $row);

	} elseif($row2["id"] == '68' || $row2["id"] == '69' || $row2["id"] == '70' || $row2["id"] == '93' || $row2["id"] == '145') {
		$queryURL = "https://stopkredit71.bitrix24.ru/rest/2184/ug2cf1vutlxh19xt/crm.lead.add.json";

		if($row2["id"] == '68') {
			$region = 'Курская область';
		}
		if($row2["id"] == '69') {
			$region = 'Калужская область';
		}
		if($row2["id"] == '70') {
			$region = 'Тульская область';
		}
		if($row2["id"] == '145') {
			$region = 'Татарстан';
		}		
		if($row2["id"] == '93') {
			$region = 'Брянская область';
		}


		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Профит - " . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "229",
					"UF_CRM_ADDRESS_PROVINCE" => $region,
					"UTM_SOURCE" => "mkg",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

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

		// если произошла какая-то ошибка - выведем её
		if(array_key_exists('error', $result)) {
			die("Ошибка при сохранении лида: " . $result['error_description']);
		}

	} elseif($row2["id"] == '71') {

		sendMessageTelegram('7014200231:AAGr8q0AIXb5UThDOTU9y9xZ29KJRpf-bF4', '-1002127674406', $row);
		
		$queryURL = "https://iburnatsev.bitrix24.ru/rest/1/iupxpw1i4rzyjz5l/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"UTM_SOURCE" => "mkg",
					"ASSIGNED_BY_ID" => 3589,
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

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

		// если произошла какая-то ошибка - выведем её
		if(array_key_exists('error', $result)) {
			die("Ошибка при сохранении лида: " . $result['error_description']);
		}

	} elseif($row2["id"] == '72') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Калуга';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '14') {
		$queryURL = "https://bankirromsk.ru/rest/261/q4t7hx0xr22z1yvv/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "357",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '73') {

		sendMessageTelegram('6662273066:AAHB2QPyZ06X8VgU0rm9WkT9tvdC5_4s0Jc', '-1002028552928', $row);

	} elseif($row2["id"] == '74') {

		sendMessageTelegram('6916674276:AAHsvvz4mRLyHHE4BY-lTPmT0OlHjnVvjpA', '-1002131040231', $row);

	} elseif($row2["id"] == '75') {

		sendMessageTelegram('6898916388:AAGR0CSBkDH9-fns-cD8KR-UrYxWejvk4K4', '-1002018171740', $row);

	} elseif($row2["id"] == '77') {

		sendMessageTelegram('6988950346:AAF9TYNW4mbzISv4Cg_CEwmEKS4Jfe-NtBI', '-1001874130571', $row);

	} elseif($row2["id"] == '78' || $row2["id"] == '79') {
		$queryURL = "https://crm.bankirro.ru/rest/59/qk3zb5nfxoealo29/crm.lead.add.json";

		if($row2["id"] == '78') {
			$region = '325';
		}
		if($row2["id"] == '79') {
			$region = '299';
		}

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => $region,
					"UTM_SOURCE" => "mkg",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '80' || $row2["id"] == '81' || $row2["id"] == '82' || $row2["id"] == '83' || $row2["id"] == '84' || $row2["id"] == '210') {
		$queryURL = "https://crm.osvobodim.com/rest/492/x7bp4wc6psyii01t/crm.lead.add.json";
		$region = $row["city"];
		$cntpart = 0;
		

		if($row2["id"] == '80') {
			$region = 'Мурманск';
		}
		if($row2["id"] == '81') {
			$region = 'Рязань';
		}
		if($row2["id"] == '82') {
			$region = 'Петрозаводск';
		}
		if($row2["id"] == '83') {
			$region = 'Сыктывкар';
		}
		if($row2["id"] == '84') {
			$region = 'Волгоград';
		}
		if($row2["id"] == '210') {
			
			$filename = $_SERVER['DOCUMENT_ROOT'] . '/partner/'. $row2["id"]. '.json';

			if(file_exists($filename)){
				
				$json = file_get_contents($filename);
				$cntpart = json_decode($json, true);
				if ($cntpart > 8) {$cntpart = 0;}
				$cntpart = $cntpart + 1;
				$json = json_encode($cntpart, JSON_UNESCAPED_UNICODE);
				file_put_contents($filename, $json);	
			  
			} else {
				$cntpart = 1;
				$json = json_encode($cntpart, JSON_UNESCAPED_UNICODE);
				file_put_contents($filename, $json);
			}
			


			if ($cntpart < 4) {
				$region = 'Волгоград';
			} elseif ($cntpart > 3 and $cntpart < 7) {
				$region = 'Рязань';
		    } elseif ($cntpart > 6 and $cntpart < 10) {
				$region = 'Петрозаводск';
		    }

		}	
		 
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид " . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . $row["vopros"],
					"UF_CRM_1599577130" => $region,
					"SOURCE_ID" => "9623667611",
					"UTM_SOURCE" => "ШоновМедиа",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
/*      

 Работаем оь шонов медиа не в коем случае не отправлять с этой почты

		$to = 'osvobodimmyrmansk@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
*/

	} elseif($row2["id"] == '85') {
		$queryURL = "https://bankrot40.bitrix24.ru/rest/385/dc5vm9nxo2acimpo/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "333",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '86') {
		$queryURL = "https://katya7878.bitrix24.ru/rest/1/mkdbn04gw6lfsz52/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "25",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '87') {

		sendMessageTelegram('7014094739:AAHBiWDpSLTf0OvG0vEDOi7VtzvvdANJytI', '-1002023540384', $row);

	} elseif($row2["id"] == '88') {

		$curl = curl_init();

		$queryData = http_build_query(
			array(
				"key" => "yic1qjwbqxnxak",
				"no_hash" => "false",
				"reclame" => 27,
				"name" => $row["fio"],
				"city_name" => $row["city"],
				"phone" => $row["phone_number"],
				"question" => $row["vopros"]
			)
		);

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => 'https://bbk.e2crm.ru/api/lead',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $queryData,
			)
		);

		$response = curl_exec($curl);

		curl_close($curl);

	} elseif($row2["id"] == '89' || $row2["id"] == '90' || $row2["id"] == '132' || $row2["id"] == '135' || $row2["id"] == '136') {
		
		if($row2["id"] == '89') {
			$queryURL = "https://uc-pravosudie.bitrix24.ru/rest/3361/z06vtcuj1yjxhr4y/crm.lead.add.json";
			$sourceid = "UC_0XD20R";
		} elseif($row2["id"] == '132') {
			$queryURL = "https://uc-pravosudie.bitrix24.ru/rest/3361/x3ab6w8arxi7xepp/crm.lead.add.json";
			$sourceid = "UC_4IXUA1";
		} elseif($row2["id"] == '135') {
			$queryURL = "https://uc-pravosudie.bitrix24.ru/rest/3361/0jbcilt3kihxfa9v/crm.lead.add.json";
			$sourceid = "UC_I3RMY1";
		} elseif($row2["id"] == '136') {
			$queryURL = "https://uc-pravosudie.bitrix24.ru/rest/3361/3x1djat9mocahcmr/crm.lead.add.json";
			$sourceid = "UC_HJUM5U";
		} else {
			$queryURL = "https://uc-pravosudie.bitrix24.ru/rest/6175/atv0b0xtd92qr3as/crm.lead.add.json";
			$sourceid = "UC_RXPKVM";
		}
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => $sourceid,
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '91') {

		$queryURL = "https://advoklad.bitrix24.ru/rest/1/f8i434sbum6mrvvj/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "1",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '92') {

		$ch = curl_init();

		$city = trim($row["city"]);
		$name = trim($row["fio"]);
		$phone = trim($row["phone_number"]);

		$queryURL = 'https://crm.fcb.expert/local/webhook/lead-add-test/index.php'
			. '?web=shonov_media'
			. '&NAME=' . curl_escape($ch, trim($row["fio"]))
			. '&PHONE=' . curl_escape($ch, trim($row["phone_number"]))
			. '&CITY=' . curl_escape($ch, $city)
			//. '&UF_CRM_1648894308=' . curl_escape($ch, $city)
			//. '&SOURCE_DESCRIPTION=MID_Мережко'
			. '&COMMENTS=' . curl_escape($ch, trim($row["vopros"]));

		curl_setopt($ch, CURLOPT_URL, $queryURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
	} elseif($row2["id"] == '94') {

		$queryURL = "https://b24-a8zl77.bitrix24.ru/rest/1/y988tdwyggftkcb1/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_HP2DDI",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '96') {

		sendMessageTelegram('7062839497:AAFmJDy0dZawbwcPMjg2FwdphfHizXk4YoI', '-1002121280866', $row);

	} elseif($row2["id"] == '98') {

		$queryURL = "https://ok-bankrot-krasnodar.bitrix24.ru/rest/15937/2ra9xpezlr88s494/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "МКГ",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "79604961781",
					"ASSIGNED_BY_ID" => "4487",
					"UTM_SOURCE" => "MGK",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		sendMessageTelegram('7034323316:AAFE-2zwKTDMXkFUR-7riHsJFdt1vG0ZKQE', '-1002025261927', $row);


	} elseif($row2["id"] == '99') {

		$queryURL = "https://ykodex.bitrix24.ru/rest/2157/796v1blaqwsuzaag/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "10",
					"UTM_SOURCE" => "MKG",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '100' || $row2["id"] == '101') {

		$queryURL = "https://ur-cpp.bitrix24.ru/rest/812/wtdp4i2s8aijs9aa/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "12",
					"UTM_SOURCE" => "MKG",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '105') {

		$queryURL = "https://bitrix.exitcenter.ru/rest/27/zxyqkff1m5e3334c/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "87",
					"UTM_SOURCE" => "MKG",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '106') {

		$queryURL = "https://bankirromsk.ru/rest/261/q4t7hx0xr22z1yvv/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "495",
					"UTM_SOURCE" => "ШоновМедиа",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '109') {

		$queryURL = "https://utrade.bitrix24.ru/rest/6/0vwf5bnz27yatpgl/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array( 
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "12",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '110') {

		$queryURL = "https://ok-bankrot.bitrix24.ru/rest/3658/dkfy6b7eys6jxj6n/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array( 
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "9056455310",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		$to = 'poletpolet8@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '111') {

		$ch = curl_init();

		$city = trim($row["city"]);
		$name = trim($row["fio"]);
		$phone = trim($row["phone_number"]);

		$queryURL = 'https://crm.fcb.expert/local/webhook/lead-add-test/index.php'
			. '?web=mkg'
			. '&NAME=' . curl_escape($ch, trim($row["fio"]))
			. '&PHONE=' . curl_escape($ch, trim($row["phone_number"]))
			. '&CITY=' . curl_escape($ch, $city)
			//. '&UF_CRM_1648894308=' . curl_escape($ch, $city)
			//. '&SOURCE_DESCRIPTION=MID_Мережко'
			. '&COMMENTS=' . curl_escape($ch, trim($row["vopros"]));

		curl_setopt($ch, CURLOPT_URL, $queryURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);

	} elseif($row2["id"] == '112') {

		$queryURL = "https://bflamulex.bitrix24.ru/rest/702/fjezr5m3khduf0qk/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "23", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '113' || $row2["id"] == '439') {

		$queryURL = "https://braincon.bitrix24.ru/rest/1342/275d1k8u889oy3p5/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "117", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '114') {

		$queryURL = "https://bfl-krasnodar.bitrix24.ru/rest/2821/olhtweeew19tugo3/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "54", 
					"UTM_SOURCE" => "saratov",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '115') {

		$queryURL = "https://bfl-krasnodar.bitrix24.ru/rest/2821/olhtweeew19tugo3/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "54", 
					"ASSIGNED_BY_ID" => "3257",
					"UTM_SOURCE" => "stavropol",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '116') {
		
		sendMessageTelegram('6916388680:AAHXj5rhScx7wk7k2yYvQrqKFepeLnCQBD0', '-1002007806003', $row);

	} elseif($row2["id"] == '117') {

		$queryURL = "https://bankrotprosto.bitrix24.ru/rest/3496/54e6gz3ss6z6phqk/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => 'Екатеринбург. ' . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_4NS4HI", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
		sendMessageTelegram('7371154948:AAH7rYeiN1G07v2umGk9mUNcoLY8lVJSY6E', '-1002189261878', $row);

	} elseif($row2["id"] == '118') {

		$queryURL = "https://bankrotprosto.bitrix24.ru/rest/3496/54e6gz3ss6z6phqk/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => 'Нижний Тагил. ' . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_4NS4HI", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
		sendMessageTelegram('7371154948:AAH7rYeiN1G07v2umGk9mUNcoLY8lVJSY6E', '-1002189261878', $row);

	} elseif($row2["id"] == '119') {

		$to = 'tap1@bb-1.ru,zma1@bb-1.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '120') {

		$to = 'tap@cepod.ru,bkg@cepod.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '121') {

		$ch = curl_init();

		$city = trim($row["city"]);
		$name = trim($row["fio"]);
		$phone = trim($row["phone_number"]);

		$queryURL = 'https://h.albato.ru/wh/38/1lfu22a/cfsUaLhZRkXqkO-vmmeYQ3Nclakx9iJPFUnt9JFVTJk/'
			. '?web=mkg'
			. '&NAME=' . curl_escape($ch, trim($row["fio"]))
			. '&PHONE=' . curl_escape($ch, trim($row["phone_number"]))
			. '&CITY=' . curl_escape($ch, $city)
			. '&COMMENTS=' . curl_escape($ch, trim($row["vopros"]));

		curl_setopt($ch, CURLOPT_URL, $queryURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		
		sendMessageTelegram('7232487276:AAFEC9pg68DunZ8I3gTZee0_hM9PHicxT3M', '-1002179338974', $row);

	} elseif($row2["id"] == '122') {

		$queryURL = "https://xn--24-8kcqfaag0coemdl6j.xn--p1ai/rest/186/p173dq5gmxnibi4i/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["city"] . '. ' . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "47", 
					"UF_CRM_1727341860" => "812", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '123') {

		$queryURL = "https://xn--24-8kcqfaag0coemdl6j.xn--p1ai/rest/186/p173dq5gmxnibi4i/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["city"] . '. ' . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "48", 
					"UF_CRM_1727341860" => "809", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		sendMessageTelegram('7929934664:AAGppCFyaCGAE1FxYk2hiJws3zmHnZaAdMg', '-1002284557298', $row);

	} elseif($row2["id"] == '124') {
		
		sendMessageTelegram('7178948918:AAHe93tDm3QPbkipwjGBNYvVqAiOyWnWjQ0', '-1002086600795', $row);

	} elseif($row2["id"] == '125') {

		$queryURL = "https://centrbr.bitrix24.ru/rest/159/gsc55ljuhl1fbdcm/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["city"] . '. ' . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "62", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '126') {

		$queryURL = "https://centrbr.bitrix24.ru/rest/159/gsc55ljuhl1fbdcm/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["city"] . '. ' . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "62", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '127') {

		require_once $_SERVER['DOCUMENT_ROOT'] . '/amo/access.php';

$names = $row["fio"];
$name = 'Новая сделка МКГ';
$phone = $row["phone_number"];
$email = 'email@gmail.com';
$target = 'Цель';
$company = 'Название компании';
$mass = $row["vopros"];

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

	} elseif($row2["id"] == '128') {

		$queryURL = "https://avangard55.bitrix24.ru/rest/721/fh5ss5nldi5lkmj8/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => 'Шонов',
					"NAME" => $row["fio"],
					"REGION" => $row["city"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "9609916053", 
					"UTM_SOURCE" => 'Allion',
					"UTM_CAMPAIGN" => 'Allion',
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '129' || $row2["id"] == '440') {

		$queryURL = "https://braincon.bitrix24.ru/rest/1342/275d1k8u889oy3p5/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => 'МНГ - ' .$row["fio"],
					"NAME" => $row["fio"],
					"REGION" => $row["city"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "61", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '131') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Брянск';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '134') {
		
		sendMessageTelegram('7203974162:AAHmrm57nIrTi9NwcVGTmpgBerD6R0IcE5E', '-1002245357126', $row);

	} elseif($row2["id"] == '137') {

		$curl = curl_init();

		$queryData = http_build_query(
			array(
				"key" => "ixbcywivtjd070",
				"no_hash" => "false",
				"reclame" => 133,
				"office" => 1,
				"name" => $row["fio"], 
				"city_name" => $row["city"],
				"phone" => $row["phone_number"],
				"question" => $row["vopros"]
			)
		);

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => 'https://agaeva.e2crm.ru/api/lead',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $queryData,
			)
		);

		$response = curl_exec($curl); 

		curl_close($curl);

	} elseif($row2["id"] == '138') {

		$curl = curl_init();

		$queryData = http_build_query(
			array(
				"key" => "shd0dj7zbhhu7w",
				"no_hash" => "false",
				"reclame" => 137,
				"office" => 2,
				"name" => $row["fio"],
				"city_name" => $row["city"],
				"phone" => $row["phone_number"],
				"question" => $row["vopros"]
			)
		);

		curl_setopt_array( 
			$curl,
			array(
				CURLOPT_URL => 'https://agaeva.e2crm.ru/api/lead',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $queryData,
			)
		);

		$response = curl_exec($curl);

		curl_close($curl);

	} elseif($row2["id"] == '139' || $row2["id"] == '193') {

		$curl = curl_init();

		$queryData = http_build_query(
			array(          
				'internalKey' => 811442945,
				'token' => 11209,
				//'keyid' => '777', // ID лида в Вашей системе (необязательное поле)
				'name' => $row["fio"],
				'phone' => $row["phone_number"],
				'question' => $row["vopros"],
				'location' => $row["city"]
			)
		);

		curl_setopt_array(  
			$curl,
			array(
				CURLOPT_URL => 'https://crm.re-lead.pro/api/cdn_api_partner',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $queryData,
			)
		);
 
		$response = curl_exec($curl);  

		curl_close($curl);

	} elseif($row2["id"] == '141') {

		$queryURL = "https://bankrotprosto.bitrix24.ru/rest/3496/54e6gz3ss6z6phqk/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => 'Новосибирск. ' . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_4NS4HI", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
		sendMessageTelegram('7371154948:AAH7rYeiN1G07v2umGk9mUNcoLY8lVJSY6E', '-1002189261878', $row);

	} elseif($row2["id"] == '142') {

		$queryURL = "https://rosuyrist.bitrix24.ru/rest/13/4isf0hfkx2fg7650/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => 'МКГ - ' . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "10", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
		sendMessageTelegram('7442438034:AAHbIyTIuC4V2ADoSD3FI7agd3alHvZQgJI', '-1002180285289', $row);
		
	} elseif($row2["id"] == '143') {

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';
		$i_mt5 = 'Город: ' . $row["city"] . '<br> ';
		$i_mt6 = 'Дата отправки: ' . date("Y-m-d") . '<br> ';

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'БФЛ.Москва';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
	} elseif($row2["id"] == '144') {

		$to = $row2["email"]; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
		
		sendMessageTelegram('7023855398:AAEPGB7MiHan_rzEFBDpRcoOa_kaASnsueo', '-1002233035568', $row);
		
	} elseif($row2["id"] == '146') {

		require_once $_SERVER['DOCUMENT_ROOT'] . '/amo/access2.php';

		$names = $row["fio"];
		$name = 'Новая сделка МКГ';
		$phone = $row["phone_number"];
		$email = 'email@gmail.com';
		$target = 'Цель';
		$company = 'Название компании';
		$mass = $row["vopros"];

		$ip = '1.2.3.4';
		$domain = 'site.ua';
		$price = 10;
		$user_amo = 31574250;

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
					array( 'id' => 1590093, 
						   'values' => array( 
									   array( 'value' => $phone ) ) ),
					array( 'id' => 1590095, 
						   'values' => array( 
									   array( 'value' => $mass ) ) ),							   
					array( 'id' => 1590097, 
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
					'id' => 328313,
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

	} elseif($row2["id"] == '148') {
		
		sendMessageTelegram('7357075869:AAEDplqcDv9hsKfmeqUgm322ifTf0n9Vcuc', '-1002199262889', $row);
		
	} elseif($row2["id"] == '150') {
		
		sendMessageTelegram('7305348442:AAFESgEmu4GJ25UBOWoPGnrcLXKyyjbQhdo', '-1002237666408', $row);
		
	} elseif($row2["id"] == '151') {
		
		sendMessageTelegram('7427468839:AAHV58cZk6zMXblgIKAo0-aOMiEAjKuxhWo', '-1002174744239', $row);
		
	} elseif($row2["id"] == '152') {

		$queryURL = "https://spbau.bitrix24.ru/rest/38/besawh7mi4b6zg7z/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "41", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '153' || $row2["id"] == '320') {

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');	

		$queryURL = "https://pravovoy.bitrix24.ru/rest/40/28hxedk62wplnpf7/crm.lead.add.json";

		$source_id = 'MKG_Iaroslavl';
		if($row2["id"] == '320') {
			$source_id = 'MKG_Ivanovo';
		}		

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => $source_id, 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

/*
		$utm_source = 'МКГ Ярославль';
			
		if($row2["id"] == '320') {
			$utm_source = 'МКГ Иваново';
		}

		$queryURL = 'https://cv-beep-btrx-resender-53dd.twc1.net/addlead/?NAME='.urlencode($row["fio"]).'&PHONE=' . $row["phone_number"].'&COMMENT=' . urlencode($row["vopros"]).'&UTM_SOURCE='.urlencode($utm_source);
		$queryData = http_build_query(
			array(
				"fields" => array(
					"NAME" => $row["fio"],
					"PHONE" => $row["phone_number"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"]
					
				),
			)
		);
		
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
*/
		if($row2["id"] == '153') {
			sendMessageTelegram('8054747802:AAFzw4d2XgF6JMti-q87dcF6I3gKqeatDNA', '-1002396089333', $row);
		}
		
		if($row2["id"] == '320') {
			sendMessageTelegram('7678223904:AAFsYLfsmICVjtoKfYAYJJTfh_a80o9V1EU', '-1002611845531', $row);
		}

	} elseif($row2["id"] == '154') {

		$queryURL = "https://lfsp.bitrix24.ru/rest/1325/emnuaahqjjfe82om/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "29", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '155') {

		$queryURL = "https://pravoved777.bitrix24.ru/rest/1/vm3pvrwgval1ztic/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "29", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '156') {
		
		sendMessageTelegram('7259915744:AAFHM_QeEpdBH0UzLXwLR34XXE5WdoQ_xLM', '-1002164725763', $row);

		require_once $_SERVER['DOCUMENT_ROOT'] . '/amo/access3.php';

		$names = $row["fio"];
		$name = 'Лид с МКГ ' . date('d.m.Y H:i');
		$phone = str_replace('++', '+','+' . trim($row["phone_number"]));
		$email = 'email@gmail.com';
		$target = 'Цель';
		$company = 'Название компании';
		$mass = $row["vopros"];

		$ip = '1.2.3.4';
		$domain = 'site.ua';
		$price = 10;
		$user_amo = 31451222;

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
					array( 'id' => 941779, 
						   'values' => array( 
									   array( 'value' => $row["city"] ) ) ),								   
					array( 'id' => 908639, 
						   'values' => array( 
									   array( 'value' => $mass ) ) ),	
					array( 'id' => 953949, 
						   'values' => array( 
									   array( 'value' => 'МКГ' ) ) ),										   
					array( 'id' => 902243, 
						   'values' => array( 
									   array( 'value' => 'Директ Премиум' ) ) ) 
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
		curl_setopt($curl, CURLOPT_COOKIEFILE, 'https://crm.mkggroup.ru/amo/cookie.txt');
		curl_setopt($curl, CURLOPT_COOKIEJAR, 'https://crm.mkggroup.ru/amo/cookie.txt');
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
			'name' => $names,
			'phone' => $phone,
			'linked_leads_id' => array($lead_id), //id сделки
			'responsible_user_id' => $user_amo, //id ответственного
			'custom_fields'=>array(
				array(
					'id' => 448331,
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
		curl_setopt($curl2, CURLOPT_COOKIEFILE, 'https://crm.mkggroup.ru/amo/cookie.txt');
		curl_setopt($curl2, CURLOPT_COOKIEJAR, 'https://crm.mkggroup.ru/amo/cookie.txt');
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

	} elseif($row2["id"] == '157') {
		
		sendMessageTelegram('7272760386:AAHPhtVybHV0w2wrIF9zD2zi0Ac9bvC850Y', '-1002180925836', $row);
		
	} elseif($row2["id"] == '159' || $row2["id"] == '185' || $row2["id"] == '232') {

		$queryURL = "https://uksodeistvie.bitrix24.ru/rest/1/6y82fvo5nh7221rw/crm.lead.add.json";

		if($row2["id"] == '159') {
			$region = 'МКГ Самара';

			sendMessageTelegram('7467295834:AAHwr2LfJDrwcCK62gYAJQ-j2JJtDIJhQJI', '-1002176331363', $row);

			/*// формируем параметры для создания лида
			$queryData = http_build_query(
				array(
					"fields" => array( 
						"TITLE" => $row["fio"],
						"NAME" => $row["fio"],
						"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
						"SOURCE_ID" => "UC_8GS53X",
						"UTM_SOURCE" => $region,
						"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
							"n0" => array(
								"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
								"VALUE_TYPE" => "WORK",			// тип номера = мобильный
							),
						), 
					),
					'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
				)
			);

			out_bitrix24($queryURL, $queryData);	*/		
		}
		if($row2["id"] == '185' || $row2["id"] == '232') {
			$region = 'МКГ РФ';

			sendMessageTelegram('7364092137:AAHPr6HypPRamIeK0456loxTkv2X-kCNoyg', '-1002340614229', $row);			

		}

			$link = 'https://flagman05.envycrm.com/openapi/v1/lead/set/?api_key=f740791e83cefd5a1f75954a7466ab36ba3a3b10';
			$curl = curl_init();

			$data = [
				'method' => 'create', // метод, 'create' - для создания, 'update' - для обновления, в данном случае использовать нет необходимости
				'inbox_type_id' => 1409225, // id типа входящего обращения
				'pipeline_id' => 283771, // id воронки сделки 
				'values' => [ // массив значений системных и произвольных полей
					'name' => $row["fio"], // имя
					'phone' => $row["phone_number"], // телефон
					'email' => "", // email
					'comment' => $row["city"] . '. ' . $row["vopros"],
					'utm_source' => $region
				]
			];

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, $link);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(['request' => $data]));
			curl_setopt($curl, CURLOPT_HEADER, false);

			$out = curl_exec($curl);
			$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
		
	} elseif($row2["id"] == '163') {
		
		sendMessageTelegram('7488066022:AAG5t2y3JOtXcFcMqeUEM72Jnmpy67BrGtg', '-1002229811896', $row);
		
	} elseif($row2["id"] == '164') {
		
		sendMessageTelegram('7266195143:AAGzBVx5kRTpUgr2BdgGZTw24eZLeuuzVvc', '-1002178200774', $row);
		
	} elseif($row2["id"] == '158') {

		$i_mt1 = 'city: ' . $row["city"] . '<br> ';
		$i_mt2 = 'phone: ' . $row["phone_number"] . '<br> ';
		$i_mt3 = 'message: ' . $row["vopros"] . '<br> ';
		$i_mt4 = 'name: ' . $row["fio"] . '<br> ';
		$i_mt5 = 'token: ' . '123123' . '<br> ';
		
		$to = $row2["email"]; //Адресат
		//$to = 'bratva1990@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4. $i_mt5;
		sendMessageMail5($to, $from, $title, $message);

	} elseif($row2["id"] == '23') {
		
		$to = $row2["email"]; //Адресат
		//$to = 'mkgallin@gmail.com'; //Адресат
		//$to = 'bratva1990@yandex.ru'; //Адресат
		$from = 'leads@crm.mkggroup.ru'; //Отправитель
		$title = 'Лид с ЦРМ';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);	 

	} elseif($row2["id"] == '165') {

		$queryURL = "https://czd.bitrix24.ru/rest/62312/y6xyw151dgjt937p/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["city"] . '. ' . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "192", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);


	} elseif($row2["id"] == '167') {

		$queryURL = "https://moy-yurist.bitrix24.ru/rest/1/9onahb1kd0n4h685/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "RC_GENERATOR", 
					"ASSIGNED_BY_ID" => "35", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
		sendMessageTelegram('7309866110:AAFJhdHK-JaZuO24EFH8-yheHbzdo2eofRc', '-1002177387703', $row);
		
	} elseif($row2["id"] == '168') {

		$queryURL = "https://pb-svoboda.bitrix24.ru/rest/11/9kp7pom8l3zxsncd/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "79604906969", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '169') {
		
		sendMessageTelegram('7292175306:AAGTzzgStGUN7_Kd26455-ETtoY6Rn0d0JY', '-1002239936676', $row);
		
	} elseif($row2["id"] == '170') {

		//$queryURL = "https://bg1.bitrix24.ru/rest/52/qdysdblq9cdnnood/crm.lead.add.json";
		$queryURL = "https://bg1.bitrix24.ru/rest/1/xawq02q3hwp691fh/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "52", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		$to = 'bglp-l@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
		sendMessageTelegram('7255765085:AAG0BdHO462Jol8GFU5319OxL9he5qOjpE8', '-1002388361753', $row);
		
	} elseif($row2["id"] == '171') {
		
		sendMessageTelegram('7547258446:AAFeAkMN0Ui-Fe6TTUt-n8i7RkrLPKKLYRY', '-1002174665645', $row);
		
	} elseif($row2["id"] == '172') {

		$to = '33cvdtech33@gmail.com'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '173') {

		$to = 'info.bazhena@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '174') {

		$to = 'Kav@giga-group.com'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '175') {

		$to = 'dolgov.net.bgd@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '176') {

		$queryURL = "https://fpsave.bitrix24.ru/rest/22/bvqf22c72c7ev8b4/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '+7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_OO9TZ2", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
		sendMessageTelegram('7320960305:AAGoAQSUkDB2g2NAb6-PAOte-cE9tAIJkJw', '-1002149380985', $row);
		
	} elseif($row2["id"] == '177') {
		/*
		$to = 'buglakova32@gmail.com'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		*/
		
		sendMessageTelegram('7308063964:AAH1yekgtv8opYNpPavJ65Q77eoLzVxLJHw', '-1002229490772', $row);
		
	} elseif($row2["id"] == '178') {
		
		sendMessageTelegram('7336537263:AAFCBsVLaDcknOPMNh6VMT-OmD_YbAh3P1w', '-1002200371289', $row);
		
	} elseif($row2["id"] == '179') {

		$queryURL = "https://tsentrpodderzhkidolzhnikov.bitrix24.ru/rest/1/8apnm6zp2v28t30v/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_5DN2G2", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '180') {

		$to = 'mmax9020@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
		sendMessageTelegram('7517079198:AAHYb4jrIv2zKwPFF7HCCMUsL0lTFYInLJA', '-1002101471645', $row);
		
	} elseif($row2["id"] == '181') {

		$to = 'mmax9020@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
		sendMessageTelegram('6559805102:AAGJC2uxr6HAZuRla8C1ZDIuXoGLnom_e6M', '-1002451254120', $row);
		
	} elseif($row2["id"] == '182') {
		
		$to = 'mmax9020@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
		sendMessageTelegram('7327682672:AAGeUF2yMM7vCHZE4IVhpDnupxl2WuccuMM', '-1002467623506', $row);
		
	} elseif($row2["id"] == '186') {
		
		sendMessageTelegram('7111351533:AAHwqSmyECES0pu4Gew5VoG_xcB1yBfpzWc', '-1002412724914', $row);
		
	} elseif($row2["id"] == '187') {

		$to = 'agorlin@rambler.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
		sendMessageTelegram('7909598425:AAFOifsh-4ISH2RqI12EGeAfrkW3WeBKi5g', '-1002366362362', $row);
		
	} elseif($row2["id"] == '188') {

		$queryURL = "https://xn--24-8kcqfaag0coemdl6j.xn--p1ai/rest/198/wwwkmqif5neaybcs/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "67", 
					"UF_CRM_1727341860" => "810", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
			
	} elseif($row2["id"] == '189') {
		
		sendMessageTelegram('7885055047:AAF9xzvgCAiqXXpdRx0il_GeyF3L3U9I3OA', '-1002380834247', $row);
		
	} elseif($row2["id"] == '190' || $row2["id"] == '191' || $row2["id"] == '192' || $row2["id"] == '223' || $row2["id"] == '224' || $row2["id"] == '225' || $row2["id"] == '226' || $row2["id"] == '227' || $row2["id"] == '228' || $row2["id"] == '254' || $row2["id"] == '255' || $row2["id"] == '266' || $row2["id"] == '269' || $row2["id"] == '299' || $row2["id"] == '306' || $row2["id"] == '319' || $row2["id"] == '342' || $row2["id"] == '347' || $row2["id"] == '354' || $row2["id"] == '355'|| $row2["id"] == '369'|| $row2["id"] == '370'|| $row2["id"] == '372' || $row2["id"] == '373' || $row2["id"] == '374' || $row2["id"] == '375' || $row2["id"] == '376' || $row2["id"] == '377' || $row2["id"] == '386' || $row2["id"] == '387' || $row2["id"] == '388' || $row2["id"] == '393' || $row2["id"] == '498' || $row2["id"] == '499' || $row2["id"] == '500' || $row2["id"] == '501' || $row2["id"] == '502' || $row2["id"] == '503' || $row2["id"] == '504') {


		$city = trim($row["city"]);
		$name = trim($row["fio"]);
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		$phone = trim($row["phone_number"]);
		$comment = trim($row["vopros"]);

		$utm = 'mkg_base_high';
		if($row2["id"] == '266') {
			$utm = 'mkg_rf';
		}
		if($row2["id"] == '299') {
			$utm = 'mkg_bas_high';
		}
		if($row2["id"] == '306') {
			$utm = 'mkg_basz_high';
		}
		if($row2["id"] == '319' || $row2["id"] == '192' || $row2["id"] == '223' || $row2["id"] == '225' || $row2["id"] == '226' || $row2["id"] == '227' || $row2["id"] == '228' || $row2["id"] == '254' || $row2["id"] == '347' || $row2["id"] == '372' || $row2["id"] == '376' || $row2["id"] == '387' || $row2["id"] == '393' || $row2["id"] == '269' || $row2["id"] == '190' || $row2["id"] == '342' || $row2["id"] == '255' || $row2["id"] == '374' || $row2["id"] == '355' || $row2["id"] == '354' || $row2["id"] == '377' || $row2["id"] == '373' || $row2["id"] == '386' || $row2["id"] == '388' || $row2["id"] == '503' || $row2["id"] == '504') {
			$utm = 'mkg_base_high';
		}
		if($row2["id"] == '369' || $row2["id"] == '370') {
			$utm = 'mkg_base';
		}
		if($row2["id"] == '498' || $row2["id"] == '499' || $row2["id"] == '500' || $row2["id"] == '501' || $row2["id"] == '502') {
			$utm = 'mkg_new_high';
		}
		$params = [
		'title' => 'Лид',
		'name' => $name,
		'phone' => $phone,
		'utm' => $utm,
		'city' => $city,
		'comment' => $comment
		];
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL =>
		'https://api.getlead.pro/api/v1/Phd7iLYRpmsrIK1l7vWKJCOfyjcRCP62/lead/add',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_HTTPHEADER => array(
		'Accept: application/json'
		),
		CURLOPT_POSTFIELDS => http_build_query($params),
		));
		$response = curl_exec($curl);
		curl_close($curl);

		if($row2["id"] == '355') {
			sendMessageTelegram('7610130623:AAFjQRf6leE9ydJitsrqbtD7w0qBbYnn80o', '-1002641077454', $row);
		}
		if($row2["id"] == '369') {
			sendMessageTelegram('7188742723:AAEd02qJE23c9T09oPVsvjrqtTVzYjqQENU', '-1002743620481', $row); 
		} 
		if($row2["id"] == '370') {
			sendMessageTelegram('7720251812:AAH8ZC_f2lA1LJw7fmVzC8qptdl0s_Z3vM8', '-1002821313026', $row); 
		}
	} elseif($row2["id"] == '194') {
/*
		$queryURL = "https://reshenie64.bitrix24.ru/rest/1/j0gjxvtqdkvxqpsh/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "41", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
*/

		$to = 'Stusha143@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
		sendMessageTelegram('7826304974:AAE2VTkSenEHa6dqaddxwSlr_JtMQtAmYZA', '-1002664591108', $row);

	} elseif($row2["id"] == '195') {

		$queryURL = "https://tarangroup.bitrix24.ru/rest/26/tbyhhlkyxwchll62/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид " . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "53",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '196') {

		$queryURL = "https://golubitskiy.bitrix24.ru/rest/22/lojmbcqik4fsay6b/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид " . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "28",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
		sendMessageTelegram('7872410137:AAFXiBlNNv6ZAj6HWGYS-NDSUKtUyJYqyiI', '-1002422962582', $row);		

	} elseif($row2["id"] == '197') {
		
		sendMessageTelegram('7034693473:AAEu64zkeXGnRX8NWL6FPFiZXyWeszHD_k8', '-1002395324277', $row);		

	} elseif($row2["id"] == '198') {

		$queryURL = "https://zkredit24.ru/rest/69241/uxolfjtud6xuw34s/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "28", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '199') {

			sendMessageTelegram('7651913228:AAELiBKCtA85QqzXPKWCChjZwxqlrnZKAIk', '-1002278400633', $row);	

			$queryURL = "https://b24-478tqu.bitrix24.ru/rest/22/u8vn420qll9dm76e/crm.lead.add.json";
			
			// формируем параметры для создания лида
			$queryData = http_build_query(
				array(
					"fields" => array(
						"TITLE" => $row["fio"],
						"NAME" => $row["fio"],
						"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
						"SOURCE_ID" => "UC_8AZ1BC", 
						'ASSIGNED_BY_ID' => 1,
						"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
							"n0" => array(
								"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
								"VALUE_TYPE" => "WORK",			// тип номера = мобильный
							),
						), 
					),
					'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
				)
			);

			out_bitrix24($queryURL, $queryData);
	} elseif($row2["id"] == '200') {
		
		sendMessageTelegram('7826309999:AAHrXJ-Ik5QkQ_Iu5TrGHUKo471UdFEUUFc', '-1002278874724', $row);	
				
	} elseif($row2["id"] == '201' || $row2["id"] == '202' || $row2["id"] == '203' || $row2["id"] == '204' || $row2["id"] == '205' || $row2["id"] == '206' || $row2["id"] == '207'|| $row2["id"] == '216'|| $row2["id"] == '217'|| $row2["id"] == '218'|| $row2["id"] == '219'|| $row2["id"] == '220' || $row2["id"] == '233' || $row2["id"] == '241' || $row2["id"] == '251'|| $row2["id"] == '252'|| $row2["id"] == '253') {

		$city = mb_strtoupper(trim($row["city"]));
		$name = trim($row["fio"]);
		$question = trim($row["vopros"]);
		$phone = trim($row["phone_number"]);
		$token_traffic = 'f87a2842b0f2a3d543a124df3fa8bc73';

		if (str_contains($city, 'КАЛИНИНГРАД')) {
		 $city = 'Калининград';
		} else if (str_contains($city, 'ЕКАТЕРИНБУРГ') || str_contains($city, 'ЕКБ')) {
		 $city = 'Екатеринбург';
		} else if (str_contains($city, 'РОСТОВ')) {
		 $city = 'Ростов';
		} else if (str_contains($city, 'ТОМСК')) {
		 $city = 'Томск';
		} else if (str_contains($city, 'ЧЕБОКСАРЫ')) {
		 $city = 'Чебоксары';
		} /*else if (str_contains($city, 'МОСК') || str_contains($city, 'МСК')) {
		 $city = 'Московская обл';
		}else if (str_contains($city, 'ПРИМ')) {
		 $city = 'Приморский край';
		}else if (str_contains($city, 'ЧУВА')) {
		 $city = 'Чувашская Республика';
		}else if (str_contains($city, 'БАШ')) {
		 $city = 'Республика Башкортостан';
		}else if (str_contains($city, 'СВЕР')) {
		 $city = 'Свердловская обл';
		}*/else if (str_contains($city, 'САНКТ') || str_contains($city, 'СПБ')) {
		 $city = 'Санкт-Петербург';
		}

		if($row2["id"] == '241') {
			$token_traffic = 'c0083f569bab9c0af70e9117f8aa9652';
		}

		$params = [
		'token' => 'iKHkJDzqlJ357EjaEgg6smKMBjeaxeRStIyc',
		'token_traffic' => $token_traffic,
		'name' => $name,
		'phone' => $phone,
		'question' => $question, 
		'city' => $city
		];
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL =>
		'https://api.dostupnoepravo.ru/lead/main/create-partners',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_HTTPHEADER => array(
		'Accept: application/json'
		),
		CURLOPT_POSTFIELDS => http_build_query($params),
		));
		$response = curl_exec($curl);
		curl_close($curl);


	} elseif($row2["id"] == '208') {

		$to = 'kras.pravo24@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
		sendMessageTelegram('8083627358:AAHo4C9gFtraq0AWLCYdqHkXAlrFhxbEvtI', '-1002386249954', $row);	
				
	} elseif($row2["id"] == '209') {
		
		sendMessageTelegram('7144818242:AAHDsJOOtpqpCYHiePCA3gIHHbzQgu2TcJs', '-1002479027200', $row);	
				
	} elseif($row2["id"] == '211') {

		$to = 'sudkrf@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
		sendMessageTelegram('7308852064:AAG1pE7SBs7FiahnpxAFWm4ZTAehewZuOc4', '-1002499162777', $row);	
				
	} elseif($row2["id"] == '212') {
		
		$queryURL = "https://crm.osvobodim.com/rest/492/x7bp4wc6psyii01t/crm.lead.add.json";
		
		$region = 'Сыктывкар';
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид " . $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["vopros"],
					"UF_CRM_1599577130" => $region,
					"SOURCE_ID" => "9623667611",
					"UTM_SOURCE" => "Шонов",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					),
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '213') {
		
		sendMessageTelegram('7846278337:AAE2ikns9SSl5nMbr0Gqf9xn2uFRbqSqwHA', '-1002492092976', $row);
				
	} elseif($row2["id"] == '214') {
		
		sendMessageTelegram('8012924324:AAExADe3Js64BxhKpmZb2I5Y4Z8Upn2sVIU', '-1002499047319', $row);	
				
	} elseif($row2["id"] == '221') {

		$queryURL = "https://b24-hdedxi.bitrix24.ru/rest/59/wonhr9bb05gehcaa/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "41", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '222') {

		$queryURL = "https://b24-6s4h99.bitrix24.ru/rest/1/auutl57t8ypksqs8/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "3", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
		sendMessageTelegram('7547387096:AAGlMtZsh2-ni_9X-2D5LzGlKqKcF6RlgxQ', '-1002324233848', $row);	
				
	} elseif($row2["id"] == '229') {

		$queryURL = "https://bankirromsk.ru/rest/261/x8ci14yw7f8xzi39/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_VQFKK8", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '230') {
		
		sendMessageTelegram('7865334472:AAFVojjvlpj6Yr4YFMMFXDhrVttl_57qJ_o', '-1002304483810', $row);	
				
	} elseif($row2["id"] == '231') {

		$queryURL = "https://sovest.bitrix24.ru/rest/56/5cln5om2za1lqzio/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "41", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '234') {
		
		sendMessageTelegram('8011853381:AAEVPnRz2X5sH5vVBbPSk_gh41iGiTJ-sxQ', '-1002333760590', $row);

		$queryURL = "https://shmeleva.bitrix24.ru/rest/1/efin5ly92cik23rc/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "41", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);			
				
	} elseif($row2["id"] == '235') {

		sendMessageTelegram('7294980013:AAGPdgSK7-Z1ZOStA6s07Ia8luFGb3nljUk', '-1002297124350', $row);
				
	} elseif($row2["id"] == '236' || $row2["id"] == '244') {

		$to = 'mail@wincrm.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
		sendMessageTelegram('7046579053:AAHhpjQ6CQRb3kfLpst-LAMoe40VKcaSw00', '-1002492471276', $row);
				
	} elseif($row2["id"] == '237') {

		$city = trim($row["city"]);
		$name = trim($row["fio"]);
		$question = trim($row["vopros"]);
		$phone = trim($row["phone_number"]);

		$params = [
		'Key' => 'X-Token',
		'Value' => '8XGhUYqzaZSctneTCCY5B3OeTusEoa4xklSJ',
		'name' => $name,
		'phone' => $phone,
		'comment' => $question, 
		'location' => $city
		];
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL =>
		'https://first.llcrm.ru/api/test-webhook',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_HTTPHEADER => array( 
		'Accept: application/json', 
		'X-Token: 8XGhUYqzaZSctneTCCY5B3OeTusEoa4xklSJ'
		),
		CURLOPT_POSTFIELDS => http_build_query($params),
		));
		$response = curl_exec($curl);

		$errno = curl_errno($curl);
		if ($errno) {
			$message = curl_strerror($errno);
			echo "cURL error ({$errno}):\n {$message}"; // Выведет: cURL error (35): SSL connect error
		}

		curl_close($curl);
				
	} elseif($row2["id"] == '238' || $row2["id"] == '438') {

		$queryURL = "https://braincon.bitrix24.ru/rest/1342/275d1k8u889oy3p5/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "118", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '239' || $row2["id"] == '436') {

		$queryURL = "https://kontora.bitrix24.ru/rest/27839/swlzrhwhl6c8uqln/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "81", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		sendMessageTelegram('7933213242:AAFNT_xtr6QO2sVhUXhEKMBv_RYV_w8O5y8', '-1002691134507', $row);
		
	} elseif($row2["id"] == '240') {

		$to = 'vprave.info@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);

		sendMessageTelegram('7591040353:AAHuKPD7Or4hBFSGJRD4lhjdBHuS1FEQUDs', '-1002443731303', $row);	
		
	} elseif($row2["id"] == '102') {

		/*// сюда нужно вписать токен вашего бота
		define('TELEGRAM_TOKEN', '6621246012:AAETG_NFFWj4KFDZbDqwqRZf4SC0WHIgCv4');

		// сюда нужно вписать ваш внутренний айдишник
		define('TELEGRAM_CHATID', '-1002044684629');


		function message_to_telegram($text) {
			$ch = curl_init();
			curl_setopt_array(
				$ch,
				array(
					CURLOPT_URL => 'https://api.telegram.org/bot' . TELEGRAM_TOKEN . '/sendMessage',
					CURLOPT_POST => TRUE,
					CURLOPT_RETURNTRANSFER => TRUE,
					CURLOPT_TIMEOUT => 10,
					CURLOPT_POSTFIELDS => array(
						'chat_id' => TELEGRAM_CHATID,
						'text' => $text,
					),
				)
			);
			curl_exec($ch);
		}


		//%0A
		$v_txt = trim($row["fio"]) . "\n" . trim($row["phone_number"]) . "\n" . trim($row["city"]) . "\n" . trim($row["vopros"]);


		message_to_telegram($v_txt);*/

		$i_mt1 = 'Телефон: ' . $row["phone_number"] . '<br> ';
		$i_mt2 = 'ФИО: ' . $row["fio"] . '<br> ';
		$i_mt3 = 'Описание: ' . 'Лид' . '<br> ';
		$i_mt4 = 'Комментарий: ' . $row["vopros"] . '<br> ';

		//$to = 'anastasiiamot@yandex.ru'; //Адресат
		$to = 'anastasiiamot@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);


	} elseif($row2["id"] == '243') {
		
		sendMessageTelegram('7830322177:AAEwYsoXc4koGmN8-s6OXCD4H6pd4_iQq3A', '-1002279066411', $row);	
				
	} elseif($row2["id"] == '245' || $row2["id"] == '348' || $row2["id"] == '426' || $row2["id"] == '433' || $row2["id"] == '464') {
		
		$curl = curl_init();

		if($row2["id"] == '245') {
			$reclame = 141;
			$office = 10;
		}
		elseif($row2["id"] == '426' || $row2["id"] == '433') {
			$reclame = 184;
			$office = 8;
		}
		elseif($row2["id"] == '464') {
			$reclame = 188;
			$office = 8;
		}
		else {
			$reclame = 161;
			$office = 8;			
		}

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		$queryData = http_build_query(
			array(
				"key" => "a1g7x5mzrw09z4",
				"no_hash" => "false",
				"reclame" => $reclame,
				"office" => $office,
				"name" => $row["fio"],
				"city_name" => $row["city"],
				"phone" => $row["phone_number"],
				"question" => $row["vopros"]
			)
		);

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => 'https://stopkredit.e2crm.ru/api/lead',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $queryData,
			)
		);

		$result = curl_exec($curl);
		curl_close($curl);
		//$result = json_decode($result, 1);
		//echo '<pre>'; print_r($result); echo '</pre>'; die();
				
	} elseif($row2["id"] == '246') {
		
		sendMessageTelegram('7741539503:AAE-eIfdOTQT34pEse_39WOBCqRWSYeO-98', '-1002287452439', $row);	
				
	} elseif($row2["id"] == '247') {
		
		sendMessageTelegram('7753036677:AAFdxCDihNRw5CBsq6IXBPggdgZxDTCEIHY', '-1002310158158', $row);	

		$city = trim($row["city"]);
		$name = trim($row["fio"]);
		$question = trim($row["vopros"]);
		$phone = trim($row["phone_number"]);

		$params = [
		'utm' => 'МКГ',	
		'name' => $name,
		'phone' => $phone,
		'question' => $question, 
		'city' => $city
		];
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL =>
		'https://tglk.ru/in/5Edypcb6NC9XjDvY',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_HTTPHEADER => array( 
		'Accept: application/json'
		),
		CURLOPT_POSTFIELDS => http_build_query($params),
		));
		$response = curl_exec($curl);

		$errno = curl_errno($curl);
		if ($errno) {
			$message = curl_strerror($errno);
			echo "cURL error ({$errno}):\n {$message}"; // Выведет: cURL error (35): SSL connect error
		}

		curl_close($curl);
				
	} elseif($row2["id"] == '248' || $row2["id"] == '249') {

		$queryURL = "https://b24-rlsdyj.bitrix24.ru/rest/114/tjne6f13ctce5tb5/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["vopros"],
					"SOURCE_ID" => "15",
					"ADDRESS" => $row["city"], 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '250') {

		$ch = curl_init();
			
		$cleaned_phone_number = preg_replace('/\D/', '', $row["phone_number"]);

		if(preg_match('/^[87]/', $cleaned_phone_number)) {
			$cleaned_phone_number = substr($cleaned_phone_number, 1);
		}	
		$cleaned_phone_number = '7'.$cleaned_phone_number;
		
		$queryURL1 = 'https://crm-zennit.ru/local/shishkin-ink/partners/search.php'
			. '?api=0D67OR2FUphfuyYqcrXB5GAdz1ELMJCl'
			. '&phone=' . curl_escape($ch, trim($cleaned_phone_number));

		curl_setopt($ch, CURLOPT_URL, $queryURL1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		
		$data = json_decode($result, true);

		//echo '<pre>'; print_r($data); echo '</pre>'; die();

		if ($data['response']['lead']['0'] > 0 or 
			$data['response']['lead']['1'] > 0 or 
			$data['response']['deal']['0'] > 0 or 
			$data['response']['deal']['1'] > 0 ) 
		{
			
			
			$ch3 = curl_init();

			$queryURL2 = 'https://crm.mkggroup.ru/api/rest.php'
				. '?param=addlead'
				. '&key=fa9sgwl456gjs9g456dsjlgjd456sjg45345789lsdlgs'
				. '&status=1'
				. '&id=' . $row["id"];

			curl_setopt($ch3, CURLOPT_URL, $queryURL2);
			curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
			$result3 = curl_exec($ch3);
			curl_close($ch3);	
			
			header('Location: ' . $_SERVER['HTTP_REFERER']);
	
		} else {

			$comments1 = mb_substr($row["vopros"], 
			mb_strpos($row["vopros"], '1.') + 2, 
			mb_strpos($row["vopros"], '2.') - mb_strpos($row["vopros"], '1.') - 2);

			$comments1 = str_replace("Сумма задолженности", "", $comments1);
			$comments1 = str_replace("-", "", $comments1);

			$comments2 = mb_substr($row["vopros"], 
			mb_strpos($row["vopros"], '2.') + 2, 
			mb_strpos($row["vopros"], '3.') - mb_strpos($row["vopros"], '2.') - 2);

			$comments2 = str_replace("Задолженность по кредитам и МФО", "", $comments2);
			$comments2 = str_replace("Задолженность по кредитам", "", $comments2);
			$comments2 = str_replace("-", "", $comments2);

			$comments2_1 = mb_substr($row["vopros"], 
			mb_strpos($row["vopros"], '3.') + 2, 
			mb_strpos($row["vopros"], '4.') - mb_strpos($row["vopros"], '3.') - 2);

			$comments2_1 = str_replace("Просрочки по платежам", "", $comments2_1);
			$comments2_1 = str_replace("-", "", $comments2_1);

			$comments3 = mb_substr($row["vopros"], 
			mb_strpos($row["vopros"], '4.') + 2, 
			mb_strpos($row["vopros"], '5.') - mb_strpos($row["vopros"], '4.') - 2);

			$comments3 = str_replace("Ипотеки,авто кредиты,залога", "", $comments3);
			$comments3 = str_replace("-", "", $comments3);

			$comments4 = mb_substr($row["vopros"], 
			mb_strpos($row["vopros"], '5.') + 2, 100);

			$comments4 = str_replace("Имущество", "", $comments4);
			$comments4 = str_replace("-", "", $comments4);

			$comments = "
			Город/область: ".$row["city"]." <br>
			Сумма долга и тип кредита: ".$comments1." " .$comments2. " <br>
			Залог: ".$comments3." <br>
			Просрочки: ".$comments2_1." <br>
			дата открытия/закрытия ИП (для ВБФЛ) <br>
			Доход: <br>
			ООО: <br> 
			Имущество: ".$comments4." <br>
			Сделки за последний год: <br>
			Семейное положение: <br>
			Совместно нажитое имущество: <br>
			Несовершеннолетние дети: <br>
			Обращение в другую компанию: <br>
			Причина обращения: <br>
			";
			
			$ch3 = curl_init();

			$queryURL2 = 'https://crm.mkggroup.ru/api/rest.php'
				. '?param=addlead'
				. '&key=fa9sgwl456gjs9g456dsjlgjd456sjg45345789lsdlgs'
				. '&status=0'
				. '&id=' . $row["id"];

			curl_setopt($ch3, CURLOPT_URL, $queryURL2);
			curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
			$result3 = curl_exec($ch3);
			curl_close($ch3);	
			
			//$queryURL = "https://crm-zennit.ru/rest/17802/2k24874uo0jd80nz/crm.lead.add.json";
			$queryURL = "https://crm-zennit.ru/rest/17802/rb7lyzfgre9quwdu/crm.lead.add.json";

			// формируем параметры для создания лида
			$queryData = http_build_query(
				array(
					"fields" => array(
						"TITLE" => "Лид с МКГ",
						"NAME" => $row["fio"],
						"COMMENTS" => $comments,
						//"SOURCE_ID" => "87", 
						"SOURCE_ID" => "122", 
						"UF_CRM_1724398750" => 3909,
						"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
							"n0" => array(
								"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
								"VALUE_TYPE" => "WORK",			// тип номера = мобильный
							),
						), 
					),
					'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
				)
			);

			out_bitrix24($queryURL, $queryData);
		}
	
	} elseif($row2["id"] == '257') {

		sendMessageTelegram('7056316724:AAEGpJhEjlVIv1JME2dLU9lLDXSzxQsjtsw', '-1002649359444', $row);

		$queryURL = "https://b24-ycw773.bitrix24.ru/rest/1/ln9ddmezgx6jz8nx/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "81", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);		

	} elseif($row2["id"] == '258') {

			$queryURL = "https://b24-478tqu.bitrix24.ru/rest/22/wm2s0enosdx1tk1p/crm.contact.add.json";
			
			// формируем параметры для создания лида
			$queryData = http_build_query(
				array(
					"fields" => array(
						//"TITLE" => $row["fio"],
						"NAME" => $row["fio"],
						//"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
						"SOURCE_ID" => "1", 
						//"STATUS_ID" => "UC_ZDVFGO", 
						"ASSIGNED_BY_ID" => 1,
						"PHONE" => array(	
							"n0" => array(
								"VALUE" => $row["phone_number"],	
								"VALUE_TYPE" => "WORK",			
							),
						), 
					),
					'params' => array("REGISTER_SONET_EVENT" => "N")
				)
			);

			$id_lead = out_bitrix24($queryURL, $queryData);
			//die("лид: " . $id_lead);
			$queryURL2 = "https://b24-478tqu.bitrix24.ru/rest/22/tzrrsi2duc0sog0o/crm.deal.add.json";
			
			// Обновляем статус у сделки
			$queryData2 = http_build_query(
				array(
					"fields" => array(
						'TITLE' => $row["fio"],
						"STAGE_ID" => "UC_ZDVFGO",
						"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
						"SOURCE_ID" => "1", 
						"ASSIGNED_BY_ID" => 1,
						"CONTACT_ID" => $id_lead, 
						
					),
					"params" => array("REGISTER_SONET_EVENT" => "Y")
				)
			);

			out_bitrix24($queryURL2, $queryData2);			
			
			
			
	
	} elseif($row2["id"] == '259') {

		$queryURL = "https://rkc29.bitrix24.ru/rest/4784/q3p9stq05ie530m5/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["vopros"],
					"ADDRESS_CITY" => $row["city"],
					"SOURCE_ID" => "79062837269", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		

	} elseif($row2["id"] == '260') {

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		 
		$ch3 = curl_init();

		$queryURL2 = 'https://federal.bitrix24.ru/rest/1/faeoyh1gkqj630pl/crm.lead.add.json'
		. '?fields[NAME]=' . curl_escape($ch3, trim($row["fio"])) 
		. '&fields[PHONE][0][VALUE]=' . curl_escape($ch3, trim(str_replace('+','',$row["phone_number"]))) 
		. '&fields[PHONE][0][VALUE_TYPE]=WORK&fields[SOURCE_ID]=22&fields[UF_CRM_1727807375541]=6252';

		curl_setopt($ch3, CURLOPT_URL, $queryURL2);
		curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
		$result3 = curl_exec($ch3);
		curl_close($ch3);	
		
		sendMessageTelegram('8127172523:AAE0olW00P7kD_beKv2kfBly2RM63gjK3bs', '-1002399819785', $row);

	} elseif($row2["id"] == '261' || $row2["id"] == '293') {

		// Формируем URL с корректным протоколом и кодированием
		$queryURL1 = 'https://furist.ru/rest/1/faeoyh1gkqj630pl/crm.duplicate.get.json?phone=' . $row["phone_number"];

		// Инициализируем curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $queryURL1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// Выполняем запрос
		$result = curl_exec($ch);

		// Проверяем ошибки curl
		if ($result === false) {
			echo 'Ошибка curl: ' . curl_error($ch);
			curl_close($ch);
			die();
		}

		// Закрываем соединение
		curl_close($ch);

		// Декодируем JSON
		$data = json_decode($result, true);

		// Проверяем ошибки JSON
		if (json_last_error() !== JSON_ERROR_NONE) {
			echo 'Ошибка декодирования JSON: ' . json_last_error_msg();
			die();
		}
		if ($data['result'] == '1') {
			
			$ch3 = curl_init();

			$queryURL2 = 'https://crm.mkggroup.ru/api/rest.php'
				. '?param=addlead'
				. '&key=fa9sgwl456gjs9g456dsjlgjd456sjg45345789lsdlgs'
				. '&status=1'
				. '&id=' . $row["id"];

			curl_setopt($ch3, CURLOPT_URL, $queryURL2);
			curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
			$result3 = curl_exec($ch3);
			curl_close($ch3);	
			
			header('Location: ' . $_SERVER['HTTP_REFERER']);	
			
		} else {	
			
			$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
			
			$ch3 = curl_init();

			$queryURL2 = 'https://federal.bitrix24.ru/rest/1/faeoyh1gkqj630pl/crm.lead.add.json'
			. '?fields[NAME]=' . curl_escape($ch3, trim($row["fio"])) 
			. '&fields[PHONE][0][VALUE]=' . curl_escape($ch3, trim(str_replace('+','',$row["phone_number"]))) 
			. '&fields[PHONE][0][VALUE_TYPE]=WORK&fields[SOURCE_ID]=22&fields[UF_CRM_1727807375541]=6250';

			curl_setopt($ch3, CURLOPT_URL, $queryURL2);
			curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
			$result3 = curl_exec($ch3);
			curl_close($ch3);

			if($row2["id"] == '261') {
				sendMessageTelegram('7918330262:AAFFG1sBEeH1P69OmJGfZIeQCIM3ZT20Nb0', '-1002457666110', $row);	
			}

			if($row2["id"] == '293') {
				sendMessageTelegram('7911369226:AAHEG3Vwv73bPEjGex3lKmCBY5NupPbbJcM', '-1002282744383', $row);	
			}		


		}

	} elseif($row2["id"] == '262') {

		$queryURL = "https://finzashita18.bitrix24.ru/rest/23/ozs38ohsjw7o8ngx/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "26", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '263') {

		$queryURL = "https://proffinans.bitrix24.ru/rest/6/km7gg94jeaz4tm62/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "М Консалтинг",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "39", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		//sendMessageTelegram('7945301530:AAFJQTV0vaiXlT0aaWPvzXt0AHVb-IsgnTE', '-1002569404990', $row);	

	} elseif($row2["id"] == '264') {

		sendMessageTelegram('7790603931:AAFD3WxUYxSmk92oR3qwRcje9141D3yp0ek', '-1002365404233', $row);	
		
	} elseif($row2["id"] == '265') {

		sendMessageTelegram('7933076233:AAFOp7GShfjJV3NoxPrDWQqIuUILIOW7Ceg', '-1002332439584', $row);	
		
	} elseif($row2["id"] == '267') {

		sendMessageTelegram('7622279037:AAFvGtbOYQixYbkz2isJS6d2bdgUBQNwgYM', '-1002451793112', $row);	
		
	} elseif($row2["id"] == '268') {

		sendMessageTelegram('7802567267:AAENtfuYdsR8KTuLedO2VeOxNNgDHLrzI2g', '-1002390390030', $row);	
		
	} elseif($row2["id"] == '270') {
		
		$queryURL = "https://webjack.ru/webhooks/http/048bfb9c2c0442a6a804321de8ce1a93/";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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

	} elseif($row2["id"] == '271') {

		sendMessageTelegram('7599395409:AAFtQWzVv3uK7kQ9LQuazHK-eBb8SnnaiE8', '-1002268483289', $row);	
		
	} elseif($row2["id"] == '272') {

		$queryURL = "https://kontora.bitrix24.ru/rest/27839/ln6bsmomyopy1alk/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "9037906279", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		sendMessageTelegram('7689641302:AAE-q4IoB2EQN5jG0mO_SOFc9L0ztZZkThc', '-1002508562110', $row);

	} elseif($row2["id"] == '273') {

		$queryURL = "https://kontora.bitrix24.ru/rest/27839/mqb7b9gxwjlg1omk/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "9037906280", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
		sendMessageTelegram('8084959363:AAFEr9EblRarTeswDadxvJ5P0FkFPdmFzXs', '-1002590943938', $row);

	} elseif($row2["id"] == '274' || $row2["id"] == '291') {

		$queryURL = "https://zashchitadolzhnikov.bitrix24.ru/rest/1/6s8qaclfx77ajsyo/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "21", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '275') {

		sendMessageTelegram('7378509209:AAHPNiuBev25Ta3FCx1NMjT-rVpvP-r96Ms', '-1002263862216', $row);	
		
	} elseif($row2["id"] == '276') {
		
		$queryURL = "https://webjack.ru/webhooks/http/b6bfba2c2efb455aa3ad67477330bfb6/";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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

	} elseif($row2["id"] == '277') {

		$queryURL = "https://b24-v980dc.bitrix24.ru/rest/1/cpqhq636rgy8mbyg/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "21", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '278') {

		$queryURL = "https://ur-pr.bitrix24.ru/rest/1/584spk85zmm3g3sc/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "505", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '279') {

		$to = 'bragaev@gmail.com'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);

		sendMessageTelegram('7569509380:AAEN9d36Tzd_3IRAyNSG-q1zMuNWpdIMNlA', '-1002339173815', $row);	
		
	} elseif($row2["id"] == '280') {

		$ch = curl_init();

		$city = trim($row["city"]);
		$name = trim($row["fio"]);
		$phone = clean_phone_number_cod($row["phone_number"], '7');

		$queryURL = 'https://alliance.helpclient.ru/api/add-req-from-site/2/71'
					. '?api_id=DiBmusfPU9'
					. '&deal_id=2'
					. '&comment=' . curl_escape($ch, $row["vopros"])
					. '&6=' . curl_escape($ch, $name)
					. '&5=' . curl_escape($ch, $phone)
					. '&63=' . curl_escape($ch, $city);

		curl_setopt($ch, CURLOPT_URL, $queryURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);

		sendMessageTelegram('7659276059:AAFFXR3FY3hT6_TpK9Koa_-BM4L82uBwT_Y', '-1002358915090', $row);	

	} elseif($row2["id"] == '281') {

		sendMessageTelegram('7726966094:AAHIk_FoMAVd-PNvRd-_WH6Xfh0yvpR9q4o', '-1002347011520', $row);	
		
	} elseif($row2["id"] == '282') {

		$to = 'info@librefinance.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);

		sendMessageTelegram('7778454104:AAFeYnWWgRO6uFwu-rz9AMKQbOsZt2OskqA', '-1002283922187', $row);	
		
	} elseif($row2["id"] == '283') {

		sendMessageTelegram('7933582958:AAHJsSWVnOlwtOXeZuXqR2ZpeE8urZJTMPE', '-1002287741375', $row);	
		
	} elseif($row2["id"] == '284') {

		$queryURL = "https://krskbankrot.bitrix24.ru/rest/1/1l8g27v7vgyzdq8w/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "43", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '285') {

		sendMessageTelegram('7899988455:AAF1hG0acOXfy1GpteWsdz9WUWf4n3swx_E', '-1002379495035', $row);	
		
	} elseif($row2["id"] == '286') {

		$to = 'integratsiasiti@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);		

		sendMessageTelegram('7832847345:AAGRrasQM-P2rbIGdjOdfaALTQ9BR7uU5-0', '-1002353124349', $row);	
		
	} elseif($row2["id"] == '287') {

		$to = 'law52nn@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);		

		sendMessageTelegram('7980764576:AAEbOGzf-03L_Mv4rZDSdLedIUaluKebyC8', '-1002485417410', $row);	
		
	} elseif($row2["id"] == '288') {

		$to = 'info@fin-pravda.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);		

		sendMessageTelegram('8147308434:AAG8RDlPNUsjh-C_NgyR5yt9n9Be7EXAnBk', '-1002314738999', $row);	
		
	} elseif($row2["id"] == '289') {

		$to = 'siti2integratsia@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);	

		sendMessageTelegram('7297471640:AAGPCKqYChN-woBM1M9XAeoQUPjaSaMScFY', '-1002464714776', $row);		
		
	} elseif($row2["id"] == '290') {

		sendMessageTelegram('7607430912:AAEwczgd-o61HFWb57fc3t9zXgXpPHlltjY', '-1002451874016', $row);	
		
	} elseif($row2["id"] == '292') {

		sendMessageTelegram('8089565178:AAH4iYPqlYWaBFYrJ66gjHOk8leETBQcHcE', '-1002289202363', $row);	
		
	} elseif($row2["id"] == '294') {

		$queryURL = "https://webjack.ru/webhooks/http/b18d66a487454ef3ac4d6c2b5c6cbac1/";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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
		
	} elseif($row2["id"] == '295') {

		sendMessageTelegram('7635155470:AAFE3FRNZgH7fg0Yw5JDU-rZUHn9N3LcBow', '-1002464024773', $row);	
		
	} elseif($row2["id"] == '296') {

		$queryURL = "https://webjack.ru/webhooks/http/16689b0e284c454386e84f8e836cb5f1/";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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
		
	} elseif($row2["id"] == '297') {

		$to = 'pravo-garant17@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);	

		sendMessageTelegram('7532289050:AAG1L6PWJOayeBqixH8WJDbz9DDkK3cCamc', '-1002175382624', $row);		
		
	} elseif($row2["id"] == '298') {

		sendMessageTelegram('7660939980:AAHLweTu5iIPr1yFxcq6DLq7FZ2LcYButqY', '-1002531223645', $row);	
		
	} elseif($row2["id"] == '300') {

		$city = trim($row["city"]);
		$name = trim($row["fio"]);
		$phone = clean_phone_number_cod($row["phone_number"], '7');

		$data = [
		   'token' => '3bbce57ae7354895873b7b72a9c79322',
		   'phone' => $phone,
		   'type' => 1,
		   'policy_accept' => 1,
		   'mailings_accept' => 1,
		   'first_name' => $name,
		   'sub_id1' => SUBSTR($row["vopros"], 1, 224) ,
		   'sub_id2' => SUBSTR($row["vopros"], 225, 448) ,
		   'sub_id3' => SUBSTR($row["vopros"], 449, 698) ,
		   'sub_id4' => SUBSTR($row["vopros"], 699, 948) ,
		   'sub_id5' => SUBSTR($row["vopros"], 949, 1202) ,
		   'sub_id6' => SUBSTR($row["vopros"], 1203, 1458) ,
		   'city_fact' => $city
		];
		/**
		* @param array $data
		* @return array
		*/
		function apiProfitRequest(array $data): array
		{
		   $curl = curl_init();

		   curl_setopt_array($curl, [
			   CURLOPT_URL => 'https://api.apiprofit.ru/v1/lead/add',
			   CURLOPT_RETURNTRANSFER => true,
			   CURLOPT_POST => true,
			   CURLOPT_POSTFIELDS => http_build_query($data)
		   ]);

		   $response = json_decode(curl_exec($curl), true);
		   $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		   curl_close($curl);

		   $message = json_decode($response['message'], true);

		   return [
			   'code' => $statusCode,
			   'status' => $response['status'],
			   'message' => $message !== null ? $message : $response['message']
		   ];
		}

		var_dump(apiProfitRequest($data));

	} elseif($row2["id"] == '301') {

		$queryURL = "https://webjack.ru/webhooks/http/00bda161a0f74408a909213fa9a5f99e/";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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
		
	} elseif($row2["id"] == '302') {

		$queryURL = "https://webjack.ru/webhooks/http/0ea8dc047fec42be8d864905a3770397/";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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
		
	} elseif($row2["id"] == '303' || $row2["id"] == '309' || $row2["id"] == '343' || $row2["id"] == '344' || $row2["id"] == '345' || $row2["id"] == '351') {

		$queryURL = "https://kuzinlaw.bitrix24.ru/rest/44132/asw7jgu0p16bd69b/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "79620896508", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '304') {
		
		sendMessageTelegram('8167805048:AAGJEkGJAQ2-ln6nVRdnZPUQ9qYB96oxYYw', '-1002683659579', $row);
		
	} elseif($row2["id"] == '305') {
		
		sendMessageTelegram('7805190004:AAF2iblyHc5WcB_nJ0LZjmaH2y9uGNF-8Lo', '-1002698157485', $row);
		
	} elseif($row2["id"] == '307') {

		$queryURL = "https://webjack.ru/webhooks/http/e09f7779f97947ee81147f95eb906151/";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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
		
	} elseif($row2["id"] == '308') {
		
		sendMessageTelegram('8006450555:AAE5JL64mQsrFK6Pf3hZA7V4wlNiHgXN3Mc', '-1002532186208', $row);
		
	} elseif($row2["id"] == '310') {

		$queryURL = "https://yur-konsalt.bitrix24.ru/rest/4006/d2x7lwrxf3mj0kji/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "29", 
					"UTM_SOURCE" => "ИП Григоренко",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		sendMessageTelegram('8239010014:AAFeatWqe-351vN975ktqeAUCMciFZj2VzQ', '-1003866927243', $row);

	} elseif($row2["id"] == '311') {

		$queryURL = "https://webjack.ru/webhooks/http/0c152aa11fd84d9e88205e96bfdbd85d/";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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
		
	} elseif($row2["id"] == '312') {

		$queryURL = "https://webjack.ru/webhooks/http/02b9cda402e3456dbcc9c0dcf6daa15f/";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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
		
	} elseif($row2["id"] == '313') {

		$queryURL = "https://bankrotstvogrupp-ru.bitrix24.ru/rest/7/f7zx5c2vcp6w9j78/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "23", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '314') {

		$queryURL = "https://variant-b39.bitrix24.ru/rest/36/apxpqfosac439wj2/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "29", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '315') {

		$queryURL = "https://variant-b39.bitrix24.ru/rest/36/9fdq2xlpvedt7jsw/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "30", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '316') {
		
		$queryURL = 'https://cv-beep-btrx-resender-53dd.twc1.net/addlead/?NAME='.urlencode($row["fio"]).'&PHONE=' . $row["phone_number"].'&COMMENT=' . urlencode($row["vopros"]).'&UTM_SOURCE='.urlencode("МКГ Ярославль");
		$queryData = http_build_query(
			array(
				"fields" => array(
					"NAME" => $row["fio"],
					"PHONE" => $row["phone_number"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"]
					
				),
			)
		);
		
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
		echo '<pre>'; print_r($result); echo '</pre>'; die();

	} elseif($row2["id"] == '317' || $row2["id"] == '470') {

		$queryURL = "https://spisancredit.bitrix24.ru/rest/122/r7atx03r7r8k3tit/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		$source_id = "188";
		if($row2["id"] == '470') {
			$source_id = "212";
		}

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => $source_id, 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '318') {
		
		sendMessageTelegram('7183707424:AAEwWm9SG-WPEn_a9yYdhsiMhWuinuj48dw', '-1002527153440', $row);
		
	} elseif($row2["id"] == '321') {

		$queryURL = "https://prospisanie.innovaticalab.ru/rest/68/q2tmf6dex3gdt34t/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "4", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '322') {
		
		sendMessageTelegram('8072690980:AAGyfbTGPhLMeLmt4oog_3yOe43ABZY8SQM', '-1002588154641', $row);
		
	} elseif($row2["id"] == '323' || $row2["id"] == '324' || $row2["id"] == '333' || $row2["id"] == '334' || $row2["id"] == '335') {

		$queryURL = "https://ooobankrotstvograzhdan.bitrix24.ru/rest/1/9qlzvz2fsl7tz9vw/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		if($row2["id"] == '323') {
			$source_id = "17";
			$assigned_by_id = "328";
		}
		if($row2["id"] == '324') {
			$source_id = "18";
			$assigned_by_id = "420";
		}
		if($row2["id"] == '333') {
			$source_id = "19";
			$assigned_by_id = "22";
		}
		if($row2["id"] == '334') {
			$source_id = "20";
			$assigned_by_id = "246";
		}
		if($row2["id"] == '335') {
			$source_id = "21";
			$assigned_by_id = "328";
		}

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => $source_id, 
					"ASSIGNED_BY_ID" => $assigned_by_id,
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '325') {

		$queryURL = "https://newplan.bitrix24.ru/rest/1/rbmc63tprdcwc72q/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_4MXNHW", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '326') {

		$queryURL = "https://fcz-bankrot.bitrix24.ru/rest/1/mmmd6wwpqh24nlvu/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "17", 
					"UTM_SOURCE" => "МКГ",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '327') {

		$to = 'pochta.lid@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);	
		
		sendMessageTelegram('8016612065:AAE-wQRjVTYGsyJTcdJ7Bxc3lN_ETTW8wPY', '-1002310562629', $row);
		
	} elseif($row2["id"] == '328') {

		$queryURL = "https://fc-garant.bitrix24.ru/rest/194/qmf3ccaa4nx11p5i/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "13", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '329') {
		
		sendMessageTelegram('8051012379:AAGhJSMtiIdruRfc8Hw-aiHAToinuHTTvKA', '-1002648818015', $row);
		
	} elseif($row2["id"] == '330' || $row2["id"] == '331' || $row2["id"] == '332') {

		$to = 'mpp6922456@gmail.com'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);	

		if($row2["id"] == '330') {
			$queryURL = "https://spisatkredity04.bitrix24.ru/rest/94/g3jh0982xi3e03iu/crm.lead.add.json";
			sendMessageTelegram('7922615316:AAE2xwUs550yMezbEdY3mtJAC4llF3duZUE', '-1002696388050', $row);
		}
		
		if($row2["id"] == '331') {
			$queryURL = "https://spisatkredity04.bitrix24.ru/rest/94/2qc8of4h9zjsjot7/crm.lead.add.json";
			sendMessageTelegram('7685559272:AAF8X43PzAzZAFcUuM85ZBoe9xyp3nkmDD8', '-1002468191462', $row);
		}

	    if($row2["id"] == '332') {
	    	$queryURL = "https://spisatkredity04.bitrix24.ru/rest/94/i400tjd1xz1amlv5/crm.lead.add.json";
			sendMessageTelegram('8128805107:AAHmMiPIadwT_VUsaHoFaQSb4lYTFSkhwr0', '-1002627626515', $row);
		}

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "13", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '336' || $row2["id"] == '437') {

		$queryURL = "https://kontora.bitrix24.ru/rest/27839/gpjutls2qh8ztb12/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "9275098632", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '337') {

		$queryURL = "https://noproblemlegalcompany.bitrix24.ru/rest/1/eupmzo7fbigc9u6z/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "2", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '338') {

		$queryURL = "https://legalmethod.bitrix24.ru/rest/13614/6zcs1153njor3vmv/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "list_50046_24162", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '339' || $row2["id"] == '340' || $row2["id"] == '341') {

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		 
		$ch3 = curl_init();

		if($row2["id"] == '339') {
			$source_id = '308';
		}

		if($row2["id"] == '340') {
			$source_id = '309';
		}

		if($row2["id"] == '341') {
			$source_id = '310';
		}

		$queryURL2 = 'http://185.119.57.238:7777/sxd.fabrika-prava.ru/crm.lead.add'
		. '?fields[TITLE]=' . curl_escape($ch3, trim($row["fio"])) 
		. '&fields[NAME]=' . curl_escape($ch3, trim($row["fio"])) 
		. '&fields[PHONE][0][VALUE_TYPE]=WORK&fields[PHONE][0][VALUE]=' . curl_escape($ch3, trim(str_replace('+','',$row["phone_number"]))) 
		. '&fields[SOURCE_ID]=' . curl_escape($ch3, $source_id) . '&fields[COMMENTS]=' . curl_escape($ch3, trim($row["vopros"]));

		curl_setopt($ch3, CURLOPT_URL, $queryURL2);
		curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
		$result3 = curl_exec($ch3);
		curl_close($ch3);

	} elseif($row2["id"] == '346') {

		$to = 'info@lawcom.top'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);	
		
		sendMessageTelegram('7954417070:AAFw8WyOLCvSw40p67hQsL7e2F_Hu0MV9JA', '-1002666310912', $row);
		
	} elseif($row2["id"] == '349') {

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		 
		$ch3 = curl_init();
		$source_id = 'mkg';

		$queryURL2 = 'https://bitrix.da-bankrot.ru/local/iks/get_and_process_lead.php'
		. '?TITLE=' . curl_escape($ch3, trim($row["fio"])) 
		. '&NAME=' . curl_escape($ch3, trim($row["fio"])) 
		. '&CITY=' . curl_escape($ch3, trim($row["city"])) 
		. '&PHONE=' . curl_escape($ch3, trim(str_replace('+','',$row["phone_number"]))) 
		. '&SOURCE_ID=' . curl_escape($ch3, $source_id) . '&COMMENTS=' . curl_escape($ch3, trim($row["vopros"]));

		curl_setopt($ch3, CURLOPT_URL, $queryURL2);
		curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch3);
		curl_close($ch3);

	} elseif($row2["id"] == '350') {

		$queryURL = "https://rrpravo.bitrix24.ru/rest/271/2z6yqgsyzw1qcvs3/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "REPEAT_SALE", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '352') {
		
		sendMessageTelegram('7407450931:AAEcsL6TlaLw0ojCHDr4iPzaUQStWrN8JaI', '-1002573837654', $row);
		
	} elseif($row2["id"] == '353') {

		$queryURL = "https://masterdolg.bitrix24.ru/rest/1/3h3o2gv38z37q9ei/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "REPEAT_SALE", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '356') {
		
		sendMessageTelegram('8176379646:AAE1yqSp14X3kAP_Q5zO2IqZrX-pN_HDHy0', '-1002599261346', $row);
		
	} elseif($row2["id"] == '357') {
		
		$queryURL = "https://webjack.ru/webhooks/http/c6e4cd75dc67486bb3fd95bd350c591c/";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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

	} elseif($row2["id"] == '358') {

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		$data = array(
			'city'  => 'Санкт-Петербург',
			'phone' => $row["phone_number"],
			'dolgsumm' => '300-500'
		);		
		 
		$ch = curl_init('https://crm.fcb.expert/local/webhook/lead-add-test/index.php?web=mkg_merezko');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE)); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$res = curl_exec($ch);
		curl_close($ch);
		
	} elseif($row2["id"] == '359') {

		$to = 'vakhnov.aleks@rambler.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);	
		
		sendMessageTelegram('7942850658:AAHh7jBbVdKjmTknU4KZeeUXv0UC7oBlFUc', '-1002683581024', $row);
		
	} elseif($row2["id"] == '360' || $row2["id"] == '361' || $row2["id"] == '474') {

		$queryURL = "https://ekspertyurist.bitrix24.ru/rest/9977/ldome3b79om99ivq/crm.lead.add.json";

		$source_id = '108';
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		if($row2["id"] == '361') {
			$source_id = '109';
		} elseif($row2["id"] == '474') {
			$source_id = '174';
		}

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => $source_id, 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '362') {
		
		sendMessageTelegram('7909972036:AAHeS5DRQqfcz7dEBj_P82BoGUCpu_57KLU', '-1002325440815', $row);
		
	} elseif($row2["id"] == '363') {

		$queryURL = "https://creditmarket.bitrix24.ru/rest/1/qh3dkrk6vgbqk0j7/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "REPEAT_SALE", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '364') {

		$queryURL = "https://bankirromsk.ru/rest/261/x8ci14yw7f8xzi39/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_3DAGMJ", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '365') {

		require_once $_SERVER['DOCUMENT_ROOT'] . '/amo/access5.php';

		$names = $row["fio"];
		$name = 'Новая сделка МКГ';
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		$phone = $row["phone_number"];
		$email = 'email@gmail.com';
		$target = 'Цель';
		$company = 'Название компании';
		$mass = $row["vopros"];

		//$custom_field_id = 907617;
		//$custom_field_value = 'тест';

		$ip = '1.2.3.4';
		$domain = 'site.ua';
		$price = 10;
		$pipeline_id = 7488090;
		$user_amo = 31413946;

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
					array( 'id' => 1639549, 
						   'values' => array( 
									   array( 'value' => $phone ) ) ),
					array( 'id' => 1639551, 
						   'values' => array( 
									   array( 'value' => $mass ) ) ),							   
					array( 'id' => 1639545, 
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
					'id' => 1639561,
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

	} elseif($row2["id"] == '366') {

		$queryURL = "https://ersa.bitrix24.ru/rest/290/xtcear07zm6zz3lu/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "24", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '367') {
		
		$queryURL = "https://webjack.ru/webhooks/http/e516ffe5a21545dcba163ab6d184c788/";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Лид с crm",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => $row["phone_number"],
				),
			)
		);

		// отправляем запрос обрабатываем ответ
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

	} elseif($row2["id"] == '368') {

		$queryURL = "https://irkburo.bitrix24.ru/rest/710/9j2mdoh1ps1vb522/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "МКГ", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
		$to = 'Irkburo@inbox.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);	
		
		sendMessageTelegram('7697753783:AAEysXHi3y0voCHS6l6nRbSY2DNGdrPPF0M', '-1002558621678', $row);
		
	} elseif($row2["id"] == '371') {

		sendMessageTelegram('7681771309:AAF6wTex7SzNggl5QDvENutyv9GIR9onDBw', '-1002846730652', $row);
		
	} elseif($row2["id"] == '378' || $row2["id"] == '379') {

		$queryURL = "https://intorg71.bitrix24.ru/rest/10791/6g55p0e2oymx0ped/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		$source_id = "220";

		if($row2["id"] == '379') {
			$source_id = "221";
		}

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => $source_id, 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '380') {
		
		sendMessageTelegram('7740053474:AAELncLnZCDsRNrsp5h1-90JhberGT__DeI', '-1002805022563', $row);
		
	} elseif($row2["id"] == '381') {

		sendMessageTelegram('8361768634:AAEPup_oTe4mZ8zbDruJ5vxHo5mwqrcxHgg', '-1002705754372', $row);
		
	} elseif($row2["id"] == '382') {

		sendMessageTelegram('8408531214:AAHCtyYkVZx1O7nwyXpUvZjZknP4V2oGdwQ', '-1002744548102', $row, $row2["id"]);
		
	} elseif($row2["id"] == '383') {

		$queryURL = "https://ccmbd.bitrix24.ru/rest/14/t7m76juyrd61kl4p/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		$region = 'иные территории, включая город и космодром Байконур';

		if($row["city_group"] == '1') {
			$region = 'Алтайский край';
		} elseif($row["city_group"] == '2') {
			$region = 'Амурская область';
		} elseif($row["city_group"] == '3') {
			$region = 'Архангельская область';
		} elseif($row["city_group"] == '5' || $row["city_group"] == '80') {
			$region = 'Астраханская область';
		} elseif($row["city_group"] == '7') {
			$region = 'Белгородская область';
		} elseif($row["city_group"] == '9') {
			$region = 'Брянская область';
		} elseif($row["city_group"] == '18') {
			$region = 'Владимирская область';
		} elseif($row["city_group"] == '20') {
			$region = 'Волгоградская область';
		} elseif($row["city_group"] == '19') {
			$region = 'Вологодская область';
		} elseif($row["city_group"] == '21') {
			$region = 'Воронежская область';
		} elseif($row["city_group"] == '33' || $row["city_group"] == '76') {
			$region = 'г. Москва';
		} elseif($row["city_group"] == '48' || $row["city_group"] == '73') {
			$region = 'г. Санкт-Петербург';
		} elseif($row["city_group"] == '89') {
			$region = 'г. Севастополь';
		} elseif($row["city_group"] == '90') {
			$region = 'Донецкая народная республика';
		} elseif($row["city_group"] == '85') {
			$region = 'Еврейская автономная область';
		} elseif($row["city_group"] == '91') {
			$region = 'Забайкальский край';
		} elseif($row["city_group"] == '23') {
			$region = 'Ивановская область';
		} elseif($row["city_group"] == '25') {
			$region = 'Иркутская область';
		} elseif($row["city_group"] == '92') {
			$region = 'Кабардино-Балкарская Республика';
		} elseif($row["city_group"] == '26') {
			$region = 'Калининградская область';
		} elseif($row["city_group"] == '28') {
			$region = 'Калужская область';
		} elseif($row["city_group"] == '86') {
			$region = 'Камчатский край';
		} elseif($row["city_group"] == '93') {
			$region = 'Карачаево-Черкесская Республика';
		} elseif($row["city_group"] == '27') {
			$region = 'Кемеровская область - Кузбасс';
		} elseif($row["city_group"] == '30') {
			$region = 'Кировская область';
		} elseif($row["city_group"] == '94') {
			$region = 'Костромская область';
		} elseif($row["city_group"] == '36') {
			$region = 'Краснодарский край';
		} elseif($row["city_group"] == '37') {
			$region = 'Красноярский край';
		} elseif($row["city_group"] == '38') {
			$region = 'Курганская область';
		} elseif($row["city_group"] == '32') {
			$region = 'Курская область';
		} elseif($row["city_group"] == '113') {
			$region = 'Ленинградская область';
		} elseif($row["city_group"] == '31') {
			$region = 'Липецкая область';
		} elseif($row["city_group"] == '95') {
			$region = 'Магаданская область';
		} elseif($row["city_group"] == '112') {
			$region = 'Московская область';
		} elseif($row["city_group"] == '34') {
			$region = 'Мурманская область';
		} elseif($row["city_group"] == '96') {
			$region = 'Ненецкий автономный округ';
		} elseif($row["city_group"] == '35') {
			$region = 'Нижегородская область';
		} elseif($row["city_group"] == '97') {
			$region = 'Новгородская область';
		} elseif($row["city_group"] == '40') {
			$region = 'Новосибирская область';
		} elseif($row["city_group"] == '54') {
			$region = 'Омская область';
		} elseif($row["city_group"] == '55') {
			$region = 'Оренбургская область';
		} elseif($row["city_group"] == '42') {
			$region = 'Орловская область';
		} elseif($row["city_group"] == '43') {
			$region = 'Пензенская область';
		} elseif($row["city_group"] == '56') {
			$region = 'Пермский край';
		} elseif($row["city_group"] == '75') {
			$region = 'Приморский край';
		} elseif($row["city_group"] == '45') {
			$region = 'Псковская область';
		} elseif($row["city_group"] == '98') {
			$region = 'Республика Адыгея';
		} elseif($row["city_group"] == '88') {
			$region = 'Республика Алтай';
		} elseif($row["city_group"] == '8' || $row["city_group"] == '78') {
			$region = 'Республика Башкортостан';
		} elseif($row["city_group"] == '99') {
			$region = 'Республика Бурятия';
		} elseif($row["city_group"] == '66') {
			$region = 'Республика Дагестан';
		} elseif($row["city_group"] == '100') {
			$region = 'Республика Ингушетия';
		} elseif($row["city_group"] == '101') {
			$region = 'Республика Калмыкия';
		} elseif($row["city_group"] == '102') {
			$region = 'Республика Карелия';
		} elseif($row["city_group"] == '29') {
			$region = 'Республика Коми';
		} elseif($row["city_group"] == '103') {
			$region = 'Республика Крым';
		} elseif($row["city_group"] == '104') {
			$region = 'Республика Марий Эл';
		} elseif($row["city_group"] == '105') {
			$region = 'Республика Мордовия';
		} elseif($row["city_group"] == '106') {
			$region = 'Республика Саха (Якутия)';
		} elseif($row["city_group"] == '41') {
			$region = 'Республика Северная Осетия - Алания';
		} elseif($row["city_group"] == '60' || $row["city_group"] == '68') {
			$region = 'Республика Татарстан';
		} elseif($row["city_group"] == '52') {
			$region = 'Республика Тыва';
		} elseif($row["city_group"] == '17') {
			$region = 'Республика Хакасия';
		} elseif($row["city_group"] == '46') {
			$region = 'Ростовская область';
		} elseif($row["city_group"] == '57') {
			$region = 'Рязанская область';
		} elseif($row["city_group"] == '49') {
			$region = 'Самарская область';
		} elseif($row["city_group"] == '58') {
			$region = 'Саратовская область';
		} elseif($row["city_group"] == '107') {
			$region = 'Сахалинская область';
		} elseif($row["city_group"] == '84' || $row["city_group"] == '22' || $row["city_group"] == '70') {
			$region = 'Свердловская область';
		} elseif($row["city_group"] == '74') {
			$region = 'Смоленская область';
		} elseif($row["city_group"] == '47') {
			$region = 'Ставропольский край';
		} elseif($row["city_group"] == '50') {
			$region = 'Тамбовская область';
		} elseif($row["city_group"] == '61') {
			$region = 'Тверская область';
		} elseif($row["city_group"] == '108') {
			$region = 'Тольятти';
		} elseif($row["city_group"] == '51') {
			$region = 'Томская область';
		} elseif($row["city_group"] == '62') {
			$region = 'Тульская область';
		} elseif($row["city_group"] == '53') {
			$region = 'Тюменская область';
		} elseif($row["city_group"] == '15') {
			$region = 'Удмуртская Республика';
		} elseif($row["city_group"] == '14') {
			$region = 'Ульяновская область';
		} elseif($row["city_group"] == '87') {
			$region = 'Хабаровский край';
		} elseif($row["city_group"] == '16') {
			$region = 'Ханты-Мансийский автономный округ - Югра';
		} elseif($row["city_group"] == '13') {
			$region = 'Челябинская область';
		} elseif($row["city_group"] == '109') {
			$region = 'Чеченская республика';
		} elseif($row["city_group"] == '11') {
			$region = 'Чувашская Республика - Чувашия';
		} elseif($row["city_group"] == '110') {
			$region = 'Чукотский автономный округ';
		} elseif($row["city_group"] == '111') {
			$region = 'Ямало-Ненецкий автономный округ';
		} elseif($row["city_group"] == '12') {
			$region = 'Ярославская область';
		}

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["vopros"],
					"SOURCE_ID" => "UC_RYIB27", 
					"UF_CRM_1747145153" => $region,
					"UF_CRM_1706189801" => $row["city"],
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		// Задерживаем время, что бы они в случае чего успеть отправить сообщение по почтк
		sleep(60);
		// Проверка на дубли

		$ch = curl_init();

		$queryURL = 'https://crm.mkggroup.ru/tt.php?phone_number=' . $row["phone_number"];

		curl_setopt($ch, CURLOPT_URL, $queryURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);

		
	} elseif($row2["id"] == '384') {

		sendMessageTelegram('8296492889:AAHyyMXs9C4pw8MAALeRz2rScV9QKWoWJoM', '-1002791152406', $row);
		
	} elseif($row2["id"] == '385') {

		$queryURL = "https://intorg71.bitrix24.ru/rest/10791/6g55p0e2oymx0ped/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "222",
					"UTM_SOURCE" => "МКГ РФ", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '389') {

		sendMessageTelegram('8103130136:AAHMk70m52HrfOS9xYptSePGTJVFsU9usJ4', '-1002321325609', $row);
		
	} elseif($row2["id"] == '390') {

		$queryURL = "https://vostochnyybastion.bitrix24.ru/rest/22/7aexcgpsj3cify19/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "17",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		//$to = $row2["email"]; //Адресат
		$to = 'webprodev@yandex.ru'; //Адресат 
		$from = 'info@mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);	

	} elseif($row2["id"] == '391') {

		$queryURL = "https://bankrot40.bitrix24.ru/rest/385/8bygsdffaxfv7ymz/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "843",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif ($row2["id"] == '395' || $row2["id"] == '396' || $row2["id"] == '397' || $row2["id"] == '404' || $row2["id"] == '405' || $row2["id"] == '406' || $row2["id"] == '407') {

		$queryURL = "https://portal.caa-group.ru/rest/334/jzvdmy224nsem1xl/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		if($row2["id"] == '395') {
			$source_id = 'RF_MGK_CHLB';
		}	
		elseif($row2["id"] == '396') {
			$source_id = 'RF_MGK_IRKT';
		}	
		elseif($row2["id"] == '397') {
			$source_id = 'RF_MGK_BRNL';
		}
		elseif($row2["id"] == '404') {
			$source_id = 'RF_MGK_PERM';
		}	
		elseif($row2["id"] == '405') {
			$source_id = 'RF_MGK_TUMEN';
		}
		elseif($row2["id"] == '406') {
			$source_id = 'RF_MGK_NVSB';
		}	
		elseif($row2["id"] == '407') {
			$source_id = 'RF_MGK_KRSNDR';
		}

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => $source_id,
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '398') {

		$queryURL = "https://spisancredit.bitrix24.ru/rest/122/r7atx03r7r8k3tit/crm.lead.add.json";

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "201", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '400') {

		$to = 'saffr95@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
		
		sendMessageTelegram('8393704033:AAGGyUarTRnX5BtNGIEU8j5fL94Q0XXApVo', '-1002437203648', $row);
		
	} elseif($row2["id"] == '401') {

		$queryURL = "https://b24-xx7nry.bitrix24.ru/rest/1/hu37q5zhmn0t2xxe/crm.lead.add.json";

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "3",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		sendMessageTelegram('8438934040:AAH-1CwwVLwTQ2URlgNuZetnXMsprC9Pz20', '-1002792337129', $row);
		
	} elseif($row2["id"] == '402' || $row2["id"] == '411') {

		$url = "https://servercarousel.nssd.su/crm.lead.add";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		$source_id = 232;
		$token = 'MRdTHIcGVvX9KmRkFSLwc';
		if($row2["id"] == '402') {
			$utm_term = "bez_zaloga";
		}
		if($row2["id"] == '411') {
			$utm_term = "zalog";
		}

		$headers = [
		    "Content-Type: application/json",
		    "source: " . $source_id,
		    "token: " . $token
		];

		$data = [
		    "fields" => [
		        "TITLE" => 'От КЦ ' . $row["fio"],
		        "NAME" => $row["fio"],
		        "PHONE" => [[
		            "VALUE_TYPE" => "WORK",
		            "VALUE" => $row["phone_number"]
		        ]],
		        "UF_CRM_1664803825011" => $row["city"],
		        "COMMENTS" => $row["vopros"],
		        "SOURCE_ID" => $source_id,
		        "UTM_SOURCE" => "lidgen",
		        "UTM_MEDIUM" => "mkg",
		        "UTM_CAMPAIGN" => "call_centre",
		        "UTM_TERM" => $utm_term
		    ],
		    "params" => ["REGISTER_SONET_EVENT" => "N"]
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


		$result = curl_exec($ch);
		if(curl_errno($ch)) {
		    echo 'Ошибка cURL: ' . curl_error($ch);
		}else {
		    echo 'Ответ сервера: ' . $result;
		}
		curl_close($ch);
		//die();
		
	} elseif($row2["id"] == '403') {

		$queryURL = "https://b24-lf64e8.bitrix24.ru/rest/1/hw6nw8he3zphayyd/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_70TSRI", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '408') {

		$queryURL = "https://bankrotbalance.bitrix24.ru/rest/51/x74aeg6rnk18cjjw/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "6", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '409') {

		$queryURL = "https://bankrotsuperkonsalt.bitrix24.ru/rest/100809/3og0qd0d0dk7zb2t/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "171", 
					"ASSIGNED_BY_ID" => "100809",
					"SOURCE_DESCRIPTION" => "МКГ",
					"UTM_SOURCE" => "leadsm",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '410') {

		require_once $_SERVER['DOCUMENT_ROOT'] . '/amo/access6.php';

		$names = $row["fio"];
		$name = 'Новая сделка МКГ';
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		$phone = $row["phone_number"];
		$email = 'email@gmail.com';
		$target = 'Цель';
		$company = 'Название компании';
		$mass = $row["vopros"];
		$city = $row["city"];

		//$custom_field_id = 907617;
		//$custom_field_value = 'тест';

		$ip = '1.2.3.4';
		$domain = 'site.ua';
		$price = 10;
		$pipeline_id = 7488090;
		$user_amo = 32548754;

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
					array( 'id' => 26859, 
						   'values' => array( 
									   array( 'value' => $phone ) ) ),
					array( 'id' => 147267, 
					       'values' => array( 
						               array( 'value' => $mass ) ) ), 
					array( 'id' => 29167, 
					       'values' => array( 
						               array( 'value' => $city ) ) ), 						   
					array( 'id' => 1867419, 
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
					'id' => 26859,
					'values' => array(
						array(
							'value' => $phone,
							'enum' => 'WORK'
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

	} elseif($row2["id"] == '412') {

		$queryURL = "https://silta.bitrix24.ru/rest/1/8mpfmi26bifomwmi/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "10", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
		$to = 'Silta24@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '413') {

		sendMessageTelegram('8380896148:AAHzmtxQilr2DuVpswghlOvMGrhzLj7Wak8', '-1002886014387', $row);
		
	} elseif($row2["id"] == '414') {

		$queryURL = "https://ur-cpp.bitrix24.ru/rest/24/uyoqilvspw9tadh0/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "12", 
					"ASSIGNED_BY_ID" => 982,
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '394' || $row2["id"] == '415' || $row2["id"] == '416' || $row2["id"] == '417' || $row2["id"] == '495') {

/*
		$link = 'https://fenj.ru/webhook/lead/mkg/?api_key=812a4bD_TwXB1a16f59f03ZCF[K803fd105f1c94ba5a9';
		$curl = curl_init();

		$data = [
				'first_name' => $row["fio"],
				'phone' => $row["phone_number"],
				'city' => $row["city"], 
				'comment' => $row["city"] . '. ' . $row["vopros"]
		];

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, $link);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data );
		curl_setopt($curl, CURLOPT_HEADER, false);

		$out = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
*/

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		$curl = curl_init();

		if($row2["id"] == '394' || $row2["id"] == '417') {
			$office = 42;
		} elseif($row2["id"] == '415') {
			$office = 53;
		} elseif($row2["id"] == '416') {
			$office = 65;
		} else {
			$office = 26;
		}

		$queryData = http_build_query(
			array(
				"key" => "j7nrean2n0ewaa",
				"no_hash" => "false",
				"office" => $office,
				"reclame" => 929,
				"name" => $row["fio"], 
				"city_name" => $row["city"],
				"phone" => $row["phone_number"],
				"question" => $row["vopros"]
			)
		);

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => 'https://bsd.e2crm.ru/api/lead',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $queryData,
			)
		);

		$response = curl_exec($curl); 

		curl_close($curl);

	} elseif($row2["id"] == '418') {
		
		$queryURL = "https://bankrotstvo-legko24.bitrix24.ru/rest/90407/r9bm1l4sspejb2u8/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "9625209768", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '419') {

		sendMessageTelegram('8089828119:AAEGNoMPzxnZgA51sqB6wtR_m9-nEco4xp4', '-1003135946665', $row);
		
	} elseif($row2["id"] == '420' || $row2["id"] == '435') {
		
		$queryURL = "https://kontora.bitrix24.ru/rest/27839/mdce30o4moby8j3c/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "9275098661", 
					"UTM_SOURCE" => "МКС КЦ СПБ",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '421') {
		
		$queryURL = "https://b24-szuyl5.bitrix24.ru/rest/1/mes9xzg0bjcubkmf/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_DD8QLA", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '422') {
		/*
		$queryURL = "https://intellectactive.bitrix24.ru/rest/113/i0lcpbul3fl9tj24/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "15", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		*/
		
		sendMessageTelegram('8118981992:AAF9eXM7YMUkARzHTFWDyXJUCuxcUfFdhls', '-1003405765022', $row);

	} elseif($row2["id"] == '423') {
		
		$queryURL = "https://4959692717.bitrix24.ru/rest/110/4itzo78k4g45cgll/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "9672968439", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '424') {
		
		$queryURL = "https://4959692717.bitrix24.ru/rest/110/4itzo78k4g45cgll/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "9672968441", 
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '425') {

		sendMessageTelegram('7586951657:AAGnawHy0nm4SZu_9stw-s-x4xlVH_lI4bo', '-1003106857383', $row);
		
	} elseif($row2["id"] == '427') {

		$queryURL = "https://spisancredit.bitrix24.ru/rest/122/r7atx03r7r8k3tit/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "209",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '428') {

		$queryURL = "https://spisancredit.bitrix24.ru/rest/122/r7atx03r7r8k3tit/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "210",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '429' || $row2["id"] == '434') {

		$queryURL = "https://prospisanie.innovaticalab.ru/rest/68/sy27hfu4fpoztu6m/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "36",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '430') {

		$queryURL = "https://bankirromsk.ru/rest/261/x8ci14yw7f8xzi39/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_FNIPGS",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '431') {

		$to = 'Morozov8730@bk.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);

		sendMessageTelegram('7623698009:AAH_-Vg8TWkRdfhabkjTmn2kKAXd1wSNUSA', '-1003113970031', $row);
		
	} elseif($row2["id"] == '432') {

		sendMessageTelegram('7547679373:AAEGcIqmEYZgS7NNFTppizyewSee8l-4brU', '-1002938238317', $row);
		
	} elseif($row2["id"] == '441' || $row2["id"] == '442') {

		sendMessageTelegram('8412935295:AAGnDAyT-RlEsEgBoVOqEQahF-8-n4ej9fA', '-1003211793834', $row);
		
	} elseif($row2["id"] == '443') {

		$queryURL = "https://l-partners.bitrix24.ru/rest/10050/k04gup7rln5nlqg9/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "CPA.МКГ",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '444') {

		sendMessageTelegram('8392960719:AAER5tp6yvRYIW9L5AgkYYCOyxuSlT6vWRw', '-1003216882553', $row);
		
	} elseif($row2["id"] == '445') {

		$queryURL = "https://demetr.bitrix24.ru/rest/1/gckxheqf6jqdjnsv/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "LeadForce",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '446') {

		$queryURL = "https://bankirromsk.ru/rest/261/q4t7hx0xr22z1yvv/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"] . '. ' . $row["city"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_HGECXO",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '447' || $row2["id"] == '448' || $row2["id"] == '449' || $row2["id"] == '107' || $row2["id"] == '108') {

		if($row2["id"] == '107') {
			$source_id = "404";
			$queryURL = "https://bankrotcsd.ru/webhook/srt/tula_kts_mkg.php";
		}		

		if($row2["id"] == '108') {
			$source_id = "403";
			$queryURL = "https://bankrotcsd.ru/webhook/srt/bryansk_kts_mkg.php";
		}

		if($row2["id"] == '447') {
			$source_id = "998";
			$queryURL = "https://bankrotcsd.ru/webhook/srt/kaluga_kts_mkg.php";
		}

		if($row2["id"] == '448') {
			$source_id = "999";
			$queryURL = "https://bankrotcsd.ru/webhook/srt/lipetsk_kts_mkg.php";
		}		

		if($row2["id"] == '449') {
			$source_id = "1000";
			$queryURL = "https://bankrotcsd.ru/webhook/srt/orel_kts_mkg.php";
		}

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		$queryData = array(
				"TITLE" => $row["fio"],
				"NAME" => $row["fio"],
				"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
				"SOURCE_ID" => $source_id,
				"PHONE" => $row["phone_number"]
			);
		
		$ch = curl_init($queryURL);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($queryData, JSON_UNESCAPED_UNICODE)); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$result = curl_exec($ch);
		curl_close($ch);

		//$result = json_decode($result, 1);
		//echo '<pre>'; print_r($result); echo '</pre>'; die();

	} elseif($row2["id"] == '450') {
/*
		$queryURL = "https://pravoput.bitrix24.ru/rest/1/42emvp2blp7bvald/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "17",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
*/
		$to = 'aalaeva825@gmail.com'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);

	} elseif($row2["id"] == '451') {

		$queryURL = "https://babkrot.bitrix24.ru/rest/17/h4bbd32qa4lt3r0t/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_XHQRW8",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '452') {

		$queryURL = "https://ykvector.bitrix24.ru/rest/11/6gae2lroh9isx9jt/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "6",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '453') {

		sendMessageTelegram('8335877930:AAHv6RLU6RX1uPJX258GHFKTOoGLSIweDao', '-1003422409441', $row);
		
	} elseif($row2["id"] == '454') {

		$to = 'poleznyizvonok@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '455') {

		sendMessageTelegram('8321198123:AAGjc1Jvw_I2NL7K9GX16Gp82OD9QvcuhH8', '-1002916941062', $row);
		
	} elseif($row2["id"] == '456') {

		$queryURL = "https://b24-5iihi0.bitrix24.ru/rest/16/h37t2ci6yplaytjf/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_HELQ3B",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '457') {

		$queryURL = "https://b24-5iihi0.bitrix24.ru/rest/16/ldeeka0eljc8jscu/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_8OKXCG",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '458') {

		sendMessageTelegram('8385389228:AAHl3RWN4HUZrZnSjlaItg_hLkTCBV8ZySo', '-1003443250214', $row);
		
	} elseif($row2["id"] == '459') {

		sendMessageTelegram('8164661043:AAF7x-FmHQLJtuSF6d4DHEJfqj7SPWOwqcI', '-1003424178608', $row);
		
	} elseif($row2["id"] == '460' || $row2["id"] == '461') {

		$queryURL = "https://xn--24-8kcqfaag0coemdl6j.xn--p1ai/rest/216/b61cb9url0ozz10d/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "47",
					"CREATED_BY_ID" => 216,
					"ASSIGNED_BY_ID" => 216,
					"UTM_SOURCE" => "МКГ ЧЛБ",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		if($row2["id"] == '460') {
			sendMessageTelegram('8286711821:AAHBqcfuzgjVx6T08JfTFAhGXwin3RN6VQc', '-1003409438195', $row);
		}
		else {
			sendMessageTelegram('8539456930:AAEmo42nRWNS5pqvz-7pBe9AtAqzAMwaeTg', '-1003392994546', $row);
		}
		
	} elseif($row2["id"] == '462' || $row2["id"] == '463') {

		$queryURL = "https://xn--24-8kcqfaag0coemdl6j.xn--p1ai/rest/216/b61cb9url0ozz10d/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "48",
					"ASSIGNED_BY_ID" => 216,
					"UTM_SOURCE" => "МКГ ЕКБ",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);		

		if($row2["id"] == '462') {
			sendMessageTelegram('8217399923:AAHSFpl1M06dp3JidHhk9ueWc0_ke_Aa0ws', '-1003399460196', $row);
		} else {
			sendMessageTelegram('8553434009:AAG1XAUZfs2Ulk-oTTBv5nYaUzE259tAleY', '-1003495132768', $row);
		}
		
	} elseif($row2["id"] == '465') {

		sendMessageTelegram('7957125872:AAEkd21KxFLmKzxCLPYmdwW1is8aLTV_PDA', '-1003596811140', $row);
		
	} elseif($row2["id"] == '466') {

		sendMessageTelegram('8546250240:AAGXWC6UPgIAXPRhp21hBfMxGyKIeF5ZD4I', '-1003592898916', $row);
		
	} elseif($row2["id"] == '467') {

		//$to = $row2["email"]; //Адресат
		$to = 'webprodev@yandex.ru'; //Адресат 
		$from = 'info@mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		
		$i_mt5 = ', is_audio: ' . $row["is_audio"];
		$i_mt6 = ', row: ' . $row;
		
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);	
		
	} elseif($row2["id"] == '468') {

		sendMessageTelegram('8496096852:AAERaoNM_TcXfEKRjAEN6nbRz-wGzfVDRiY', '-1003239801530', $row);
		
	} elseif($row2["id"] == '469') {

		sendMessageTelegram('8563560828:AAHHw-hO3GtHS6btFCYgec_014qtUs8AJMQ', '-1003694478441', $row);
		
		$to = 'asmik_danielyan@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);

	} elseif($row2["id"] == '471' || $row2["id"] == '472') {
		
		$to = 'bflsamara@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);

		if($row2["id"] == '471') {
			sendMessageTelegram('8305392506:AAFfQyaxFVoVZrRbwgBl5zr2Nn1Ezuiswho', '-1003549834847', $row);
		} else {
			sendMessageTelegram('8355544675:AAHxYNewFgE2OLEwCZnHfHprlqGIdJY8AEg', '-1003476140527', $row);
		}
		
	} elseif($row2["id"] == '473') {

		$queryURL = "https://b24-rlsdyj.bitrix24.ru/rest/12062/0yz81yl7l7fhkdfm/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "42",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		sendMessageTelegram('8489645885:AAHtcPQhNw4pm4ZdS7dY-_dqGcMnrFLN8XU', '-1003581138399', $row);
		
	} elseif($row2["id"] == '475') {

		sendMessageTelegram('8548360087:AAGfs0pKsOqYtekP_-EXkwHh-Vxc0SNtaVc', '-1003639251952', $row);
		
	} elseif($row2["id"] == '476') {

		sendMessageTelegram('8227259402:AAFGZniVAckcZfHbxs4EYJJf0MchhIdaQHU', '-1003336481011', $row);
		
		$to = 'gr.bankrot@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);

		$queryURL = "https://gr-bankrot.bitrix24.ru/rest/247/xehkvw45f0getj10/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "42",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);		

	} elseif($row2["id"] == '477' || $row2["id"] == '478') {

		$queryURL = "https://bitrix.express-bankrot.ru/rest/31005/68wtvi7bnicbw8ud/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => "Партнер 28",
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "9038672860",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

	} elseif($row2["id"] == '479') {

		$queryURL = "https://b24-5gw28x.bitrix24.ru/rest/1/7un8z5gr1mn72w5o/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "3",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		sendMessageTelegram('8231622362:AAGvo_Uk7_TWZA8YrXHGpShNbgsspFA6z7U', '-1003698606293', $row);
		
	} elseif($row2["id"] == '480' || $row2["id"] == '481' || $row2["id"] == '482' || $row2["id"] == '483' || $row2["id"] == '484' || $row2["id"] == '485' || $row2["id"] == '486') {

		$queryURL = "https://api.vitakon.ru:4443/MKG-group/nl64jdfa90okiir4vf5ih0doiylkdd45/";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		$queryData = array(
				"TITLE" => $row["fio"],
				"NAME" => $row["fio"],
				"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
				"SOURCE_ID" => "25",
				"PHONE" => $row["phone_number"],
				"utm_clientid" => $row["id"]
			);
		
		$ch = curl_init($queryURL);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($queryData, JSON_UNESCAPED_UNICODE)); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$result = curl_exec($ch);
		curl_close($ch);
		
	} elseif($row2["id"] == '487' || $row2["id"] == '488' || $row2["id"] == '489') {

		$queryURL = "https://stopkredit71.bitrix24.ru/rest/14076/yqavyrk9vbbrvol4/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		if($row2["id"] == '487') {
			$region = "Брянская область";
		}
		if($row2["id"] == '488') {
			$region = "Тульская область";
		}		
		if($row2["id"] == '489') {
			$region = "Тверская область";
		}

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "213",
					"UF_CRM_ADDRESS_PROVINCE" => $region,
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '490') {

		sendMessageTelegram('8530065281:AAF287a6fHDsm_uKHG7znHvAv24xkiRYb3w', '-1003328672749', $row);
		
	} elseif($row2["id"] == '491') {

		sendMessageTelegram('8308130701:AAH4TSardFGMwo9WDTeOKMI6Ls6ZYfK7_A8', '-1003860059450', $row);
		
	} elseif($row2["id"] == '492') {

		$queryURL = "https://cpkras.bitrix24.ru/rest/51/ssv1cwggl6zlwuhb/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "215",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		sendMessageTelegram('8280856113:AAEWSp0Yok9XoQTJckHvmT26PavwOBlhrw8', '-1003562581407', $row);
		
	} elseif($row2["id"] == '493' || $row2["id"] == '494') {

		$to = 'iulina25@mail.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);	
		
		if($row2["id"] == '493') {
			sendMessageTelegram('8507941405:AAEkXoum7OgIGP1M3gap4hQ5_062NG-RgI4', '-1003719497903', $row);		
		}

		if($row2["id"] == '494') {
			sendMessageTelegram('8361085748:AAGT970-WB1knXErOMCHsk8fVs9XFX8VIVA', '-1003849013060', $row);		
		}			
		
	} elseif($row2["id"] == '496') {

		sendMessageTelegram('8605299896:AAFGn8Igtpgf2WWokc59dgQLjAmtmdWXklk', '-1003847961010', $row);
		
	} elseif($row2["id"] == '497') {

		sendMessageTelegram('8733912400:AAFdLnEVGocFwcAHq63f-aKYjfeKx8MtlNc', '-1003605555671', $row);

		require_once $_SERVER['DOCUMENT_ROOT'] . '/amo/access7.php';

		$names = $row["fio"];
		$name = 'Новая сделка МКГ';
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '8');
		$phone = $row["phone_number"];
		//$mass = $row["vopros"];
		$city = $row["city"];
		$user_amo = 13036978;
		$pipeline_id = 10131810;
		$status_id = 80276494;

		$leads['request']['leads']['add']=array(
			array(
				'name' => $name,
				'pipeline_id' => $pipeline_id, // ID воронки "Неразобранное"
				'status_id' => $status_id, //id статуса
				//'responsible_user_id' => $user_amo, //id ответственного по сделке
				//'date_create'=>1298904164, //optional
				//'price'=>300000,
				//'tags' => 'Important, USA', #Теги
				"_embedded" => array( 
					"contacts" => array(
						array( 'first_name' => $names )
					)
				),
				'custom_fields'=>array( 
					array( 'id' => 1233475, 
						   'values' => array( 
									   array( 'value' => $phone ) ) ),
					array( 'id' => 1235587, 
					       'values' => array( 
						               array( 'value' => $city ) ) ),
					array( 'id' => 1548437, 
					       'values' => array( 
						               array( 'value' => $row3["debt"] ) ) ),	
					array( 'id' => 1548461, 
					       'values' => array( 
						               array( 'value' => $row3["delays"] ) ) ),	
					array( 'id' => 1548463, 
					       'values' => array( 
						               array( 'value' => $row3["mortgage"] ) ) ),	
					array( 'id' => 1548465, 
					       'values' => array( 
						               array( 'value' => $row3["car_loan"] ) ) ),
					array( 'id' => 1548447, 
					       'values' => array( 
						               array( 'value' => $row3["real_estate"] ) ) ),
					array( 'id' => 1548449, 
					       'values' => array( 
						               array( 'value' => $row3["early_action"] ) ) ),
					array( 'id' => 1548467, 
					       'values' => array( 
						               array( 'value' => $row3["messengers"] ) ) ),
					array( 'id' => 1548777, 
					       'values' => array( 
						               array( 'value' => $row3["movables"] ) ) ),
					array( 'id' => 1548453, 
					       'values' => array( 
						               array( 'value' => $row3["additional_comment"] ) ) ),
					array( 'id' => 1553931, 
					       'values' => array( 
						               array( 'value' => $row3["audio_link"] ) ) )				               															
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
			'pipeline_id' => $pipeline_id, // ID воронки "Неразобранное"
			'status_id' => $status_id, //id статуса
			//'responsible_user_id' => $user_amo, //id ответственного
			'custom_fields'=>array(
				array(
					'id' => 1233475,
					'values' => array(
						array(
							'value' => $phone,
							'enum' => 'WORK'
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

	} elseif($row2["id"] == '506') {

		sendMessageTelegram('8724872188:AAHh2tDTHqFcDZiyVvqWDm46OkCK8Qqwkl4', '-1003769076378', $row);

		$to = 'bld-groupp@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);

	} elseif($row2["id"] == '507') {

		sendMessageTelegram('8790045190:AAGvR73gk6NLUTzQiLYGTk50gOlReHbOjNo', '-1003840922491', $row);

		$to = 'bld-groupp@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);

	} elseif($row2["id"] == '508') {

		sendMessageTelegram('8684901874:AAFx-q_EwYuea21K8WbdS_mPegzANBUC1R8', '-1003651378679', $row);

		$to = 'bld-groupp@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);

	} elseif($row2["id"] == '509') {

		sendMessageTelegram('8653639225:AAG2LUPhHrPYK5LdQdPSupjSnbJ3n0tL_Xk', '-1003702083642', $row);

		$to = 'bld-groupp@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);

	} elseif($row2["id"] == '510') {

		sendMessageTelegram('8672436582:AAFP_lFTG7f_xLLIvOCTiM-2Ali2akBsCCg', '-1003752314128', $row);

		$to = 'bld-groupp@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
				
	} elseif($row2["id"] == '511') {

		$queryURL = "https://bankirromsk.ru/rest/261/x8ci14yw7f8xzi39/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_JSJA8S",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '512') {

		$queryURL = "https://prof-zaschita.bitrix24.ru/rest/388/0ag5frpzycqrac8h/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => 'МКГ',
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "386",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);

		sendMessageTelegram('8701678046:AAG6rAqLwSxfv_YVSbT1HFLlxzYaZVi27FU', '-1003775956105', $row);
				
	} elseif($row2["id"] == '513') {

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');
		 
		$ch = curl_init('https://crm.fcb.expert/local/webhook/lead-add-test/index.php?web=DoubleCheck&phone=' . $row["phone_number"]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$result = curl_exec($ch);
		curl_close($ch);
		//echo '<pre>'; print_r($result); echo '</pre>';
		$result = json_decode($result, 1);

		if($result['result'] === false) {
			//echo 'Нет в системе: ' . $row["phone_number"];
			//die();

			sendMessageTelegram('8701678046:AAG6rAqLwSxfv_YVSbT1HFLlxzYaZVi27FU', '-1003811089980', $row);

			$array = array(
				'phone'    => $row["phone_number"],
				'city' => $row["city"],
				'comments' => $row["vopros"]
			);		
			 
			$ch2 = curl_init('https://crm.fcb.expert/local/webhook/lead-add-test/index.php?web=mkg_lead');
			curl_setopt($ch2, CURLOPT_POST, 1);
			curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($array, '', '&'));
			curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch2, CURLOPT_HEADER, false);
			$result2 = curl_exec($ch2);
			
			//echo '<pre>'; print_r($result2); echo '</pre>'; die();
			/*
			if ($result2 === false) {
			    echo 'Ошибка curl: ' . curl_error($ch2);
			} else {
			    echo '<pre>';
			    print_r($result2);
			    echo '</pre>';
			    
			    // Также можно посмотреть HTTP-код
			    $httpCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
			    echo "HTTP код: $httpCode";
			}
			*/
			curl_close($ch2);	
			//die();

		} elseif($result['result'] === true) {

			//echo 'Есть в системе: ' . $row["phone_number"];
			//die();
			
			$ch3 = curl_init();

			$queryURL2 = 'https://crm.mkggroup.ru/api/rest.php'
				. '?param=addlead'
				. '&key=fa9sgwl456gjs9g456dsjlgjd456sjg45345789lsdlgs'
				. '&status=1'
				. '&id=' . $row["id"];

			curl_setopt($ch3, CURLOPT_URL, $queryURL2);
			curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
			$result3 = curl_exec($ch3);
			curl_close($ch3);	
			
			header('Location: ' . $_SERVER['HTTP_REFERER']);

		}

	} elseif($row2["id"] == '514') {

		$queryURL = "https://bankirromsk.ru/rest/261/x8ci14yw7f8xzi39/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_XQJH14",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '515') {

		sendMessageTelegram('8701678046:AAG6rAqLwSxfv_YVSbT1HFLlxzYaZVi27FU', '-1003806326178', $row);
		
	} elseif($row2["id"] == '516') {

		$queryURL = "https://b24-rbz236.bitrix24.ru/rest/1/8gf3n6cz4qxaiphz/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_XQJH14",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '517') {

		$queryURL = "https://ekspau.bitrix24.ru/rest/34121/gtpoqvbzs6kwe8iv/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "45",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '518') {

		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		$webhookUrl = 'https://5135335-cc67306.twc1.net/webhook/21baa981-9d62-4e3a-b797-8b7af41b087b';

		$crmData = array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			);		

		$ch = curl_init($webhookUrl);
		curl_setopt_array($ch, [
		    CURLOPT_POST => true,                                   // Метод POST
		    CURLOPT_POSTFIELDS => http_build_query($crmData),       // Наш массив с данными
		    CURLOPT_RETURNTRANSFER => true,                          // Возвращать ответ
		    CURLOPT_TIMEOUT => 30,                                   // Таймаут 30 сек
		    CURLOPT_SSL_VERIFYPEER => false,                         // Отключаем проверку SSL (для теста)
		    CURLOPT_HTTPHEADER => [
		        'Content-Type: application/x-www-form-urlencoded'    // Стандартный тип для POST форм
		    ],
		    CURLOPT_FAILONERROR => false
		]);

		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curlError = curl_error($ch);
		curl_close($ch);

		/*
		$result = json_decode($result, 1);
					    
		echo 'HTTP код: ' . $httpCode;
		echo '<br>';
		if($curlError === false) {
		    echo 'Curl error: ' . $curlError;
		} else {
		    echo 'Operation completed without any errors';
		}
		echo '<pre>'; print_r($result); echo '</pre>'; die();
		*/
		
	} elseif($row2["id"] == '519') {

		$queryURL = "https://slyozi-pristava.bitrix24.ru/rest/1/7f5jxu38nrp0jxy5/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => 'МКГ',
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "8",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '520') {

		$to = 'sheiskt@gmail.com'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '521') {

		sendMessageTelegram('8701678046:AAG6rAqLwSxfv_YVSbT1HFLlxzYaZVi27FU', '-1003764189991', $row);
		
	} elseif($row2["id"] == '522') {

		$to = 'dolg.stop.rus@bk.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '523') {

		$queryURL = "https://newstart.bitrix24.ru/rest/2/gl0w78r2btt8ug0s/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => 'МКГ',
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "17",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '524') {

		$to = 'ANZNIK@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '525') {

		$to = 'agorlin@rambler.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
		
		sendMessageTelegram('8701678046:AAG6rAqLwSxfv_YVSbT1HFLlxzYaZVi27FU', '-1003753503123', $row);

	} elseif($row2["id"] == '526') {

		$to = 'info@gubenkogroup.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'МКГ - Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4 . $i_mt5 . $i_mt6;
		sendMessageMail5($to, $from, $title, $message);
		
	} elseif($row2["id"] == '527') {

		$queryURL = "https://braincon.bitrix24.ru/rest/586/2525s6xat0o095wx/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => 'Полезный Звонок',
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_KFZ7FK",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} elseif($row2["id"] == '528') {

		$queryURL = "https://b24-7d38mb.bitrix24.ru/rest/29/eagbmjs3k07j5s2z/crm.lead.add.json";
		$row["phone_number"] = clean_phone_number_cod($row["phone_number"], '7');

		// формируем параметры для создания лида
		$queryData = http_build_query(
			array(
				"fields" => array(
					"TITLE" => $row["fio"],
					"NAME" => $row["fio"],
					"COMMENTS" => $row["city"] . '. ' . $row["vopros"],
					"SOURCE_ID" => "UC_RVPT5R",
					"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
						"n0" => array(
							"VALUE" => $row["phone_number"],	// ненастоящий номер Меган Фокс
							"VALUE_TYPE" => "WORK",			// тип номера = мобильный
						),
					), 
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
			)
		);

		out_bitrix24($queryURL, $queryData);
		
	} else { 
		$to = $row2["email"]; //Адресат
		//$to = 'webprodev@yandex.ru'; //Адресат 
		$from = 'info@mkggroup.ru'; //Отправитель
		$title = 'Заявка с crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail5($to, $from, $title, $message);	
	}
}