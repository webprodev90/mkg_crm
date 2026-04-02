<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

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
			
			if ($pos > 0) {
	
				$res = $db_connect->query('SELECT count(*) as rc FROM `bez_unprocessed_base` WHERE phone_number = ' . $phone_number );					
				while( $row = $res->fetchAssoc() ){	
				    if ($row["rc"] > 0) {
						//echo '<pre>'; print_r($header); echo '</pre>';
						$db_connect->query('update `bez_unprocessed_base` set is_double = 1 WHERE phone_number = ' . $phone_number );
					}
				}	
			}

		}
			
	// закрываем соединение
	imap_close($connect_imap);
}

if (isset($_GET['phone_number'])) { 
	fromMailPhone($_GET['phone_number']);
}
