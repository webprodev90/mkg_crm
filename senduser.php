<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    $i_login = $_POST['login'];
    $i_role = '6';
    $i_name = $_POST['name'];
    $i_user = $_POST['user'];
    $i_phone = $_POST['phone'];
	$salt = salt();
	$pass = md5(md5($_POST['pass']).$salt);
	
	$db_connect->query('INSERT INTO bez_reg
							   VALUES(
										"",
										"'. $i_login .'",
										"'. $pass .'",
										"'. $salt .'",
										"'. md5($salt) .'",
										1,
										"'. $i_role .'",
										"'. $i_name .'",
										"'. $i_user .'",
										0,
										"",
										"'. $$i_phone .'"
										)');

	$loginid = $db_connect->getLastInsertId();
	
	$oper_date_start = trim(preg_replace('/[\t\n\r\s]+/', ' ', date('Y-m-d')));
	$oper_date_end = trim(preg_replace('/[\t\n\r\s]+/', ' ', date('Y-m-d')));	

	$db_connect->query('INSERT INTO settings ( name_value, date_value, login_id ) VALUES ("oper_date_start","' .$oper_date_start . '", "' .$loginid . '")');	    
	$db_connect->query('INSERT INTO settings ( name_value, date_value, login_id ) VALUES ("oper_date_end","' .$oper_date_end . '", "' .$loginid . '")');	    

		$i_mt1 = 'Телефон: ' . $i_phone . '<br> ';
		$i_mt2 = 'Имя: ' . $i_name . '<br> ';		
		$i_mt3 = 'Почта: ' . $i_login . '<br> ';		
		$i_mt4 = 'Логин: ' . $i_user . '<br> ';		


		//$to = $row2["email"]; //Адресат
		$to = 'bratva1990@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка на регистрацию вебмастера в crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3 . $i_mt4;
		sendMessageMail($to, $from, $title, $message);
		
	//echo '<pre>'; print_r($_POST); echo '</pre>';						  
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>