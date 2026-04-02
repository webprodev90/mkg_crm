<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    $i_user = $_POST['part1'];
    $i_amount = $_POST['amount'];
    $i_idval = $_POST['idval'];
	



	$res1 = $db_connect->query('SELECT * FROM `bez_reg` WHERE login = "'. $i_idval .'"');	    
	$user_id = $res1->fetchAssoc();	
	
	$res = $db_connect->query('SELECT * FROM `balance` WHERE user_id = "'. $i_user .'"');	    
	$amount = $res->fetchAssoc();		

	if($_POST["amount"]) {$i_sql1 =  'amount = "' . ($amount["amount"] + $i_amount) . '" ';}	
	
	$i_sql = 'update `balance` set ' . $i_sql1 . ' WHERE user_id = "' . $i_user . '"';	

	$db_connect->query($i_sql);	
	
    $i_text = 'Пользователь ' . $i_idval . ' добавил баланс ' . $i_amount . ' рекламодателю ' . $i_user;
    $i_value = 'update_balance';
	$i_row_change_time = date("Y-m-d");
	$i_user_id = $user_id["id"];

	
	$db_connect->query('INSERT INTO logs ( row_change_time, value, text, user_id) VALUES ("' .$i_row_change_time . '", "' .$i_value . '", "' .$i_text . '", "' .$i_user_id . '")');	    


	//echo '<pre>'; print_r($_POST); echo '</pre>';						  
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>