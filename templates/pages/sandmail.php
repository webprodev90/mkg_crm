<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    $i_login = $_POST['login'];
    $i_phone = $_POST['phone'];
    $i_name = $_POST['name'];
    $i_user = $_POST['user'];
	$salt = salt();
	$pass = md5(md5($_POST['pass']).$salt);
	

		$i_mt1 = 'Телефон: ' . $i_phone . '<br> ';
		$i_mt2 = 'Имя: ' . $i_name . '<br> ';		
		$i_mt3 = 'Почта: ' . $i_login . '<br> ';		


		//$to = $row2["email"]; //Адресат
		$to = 'bratva1990@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Заявка на регистрацию вебмастера в crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3;
		sendMessageMail($to, $from, $title, $message);

	//echo '<pre>'; print_r($_POST); echo '</pre>';						  
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>