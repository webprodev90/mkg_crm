<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    $i_login = $_POST['login'];
    $i_name = $_POST['name'];
    $i_mass = $_POST['mass'];
	

		$i_mt1 = 'Имя: ' . $i_name . '<br> ';
		$i_mt2 = 'Почта: ' . $i_login . '<br> ';		
		$i_mt3 = 'Текст: ' . $i_mass . '<br> ';			


		//$to = $row2["email"]; //Адресат
		$to = 'bratva1990@yandex.ru'; //Адресат
		$from = 'info@crm.mkggroup.ru'; //Отправитель
		$title = 'Обратная связь из crm';
		$message = $i_mt1 . $i_mt2 . $i_mt3;
		sendMessageMail($to, $from, $title, $message);
		
	//echo '<pre>'; print_r($_POST); echo '</pre>';						  
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>