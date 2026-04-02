<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    $i_login = $_POST['login'];
    $i_role = $_POST['role'];
    $i_name = $_POST['name'];
    $i_user = $_POST['user'];
    $id_otdel = $_POST['id_otdel'];
	$salt = salt();
	$pass = md5(md5($_POST['pass']).$salt);

	$res_login = $db_connect->query("SELECT * FROM bez_reg WHERE user = '{$i_user}' LIMIT 1;");
	$res_email = $db_connect->query("SELECT * FROM bez_reg WHERE login = '{$i_login}' LIMIT 1;");

	if($res_login->getNumRows() == 0 && $res_email->getNumRows() == 0 && trim($i_login) !== '' && trim($i_role) !== '' && trim($i_name) !== '' && trim($i_user) !== '' && trim($_POST['pass']) !== '') {
		
		$db_connect->query('INSERT INTO bez_reg ( login, 
												  pass, 
												  salt, 
												  active_hex, 
												  status, 
												  role, 
												  name, 
												  user, 
												  id_otdel, 
												  online, 
												  address_id,
												  view_password
												  ) VALUES(
															"'. $i_login .'",
															"'. $pass .'",
															"'. $salt .'",
															"'. md5($salt) .'",
															1,
															"'. $i_role .'",
															"'. $i_name .'",
															"'. $i_user .'",
															"'. $id_otdel .'",
															0,
															"",
															"'. $_POST['pass'] . "." . $pass .'"
															)');

		$loginid = $db_connect->getLastInsertId();
		
		$oper_date_start = trim(preg_replace('/[\t\n\r\s]+/', ' ', date('Y-m-d')));
		$oper_date_end = trim(preg_replace('/[\t\n\r\s]+/', ' ', date('Y-m-d')));	

		$db_connect->query('INSERT INTO settings ( name_value, date_value, login_id ) VALUES ("oper_date_start","' .$oper_date_start . '", "' .$loginid . '")');	    
		$db_connect->query('INSERT INTO settings ( name_value, date_value, login_id ) VALUES ("oper_date_end","' .$oper_date_end . '", "' .$loginid . '")');	    

		//echo '<pre>'; print_r($_POST); echo '</pre>';						  
		//header('Location:'. $_SERVER['HTTP_REFERER']);
		http_response_code(200);
        header('Content-Type: application/json');
		echo json_encode(['response' => 'success']);

	} else {
		$war_text = 'Невозможно добавить нового пользователя, так как ';
		if($res_login->getNumRows() > 0) {
			$war_text .= 'такой логин уже существует!';
		} 
		elseif($res_email->getNumRows() > 0) {
			$war_text .= 'такая почта уже существует!';
		} 
		elseif(trim($i_login) === '' || trim($i_role) === '' || trim($i_name) === '' || trim($i_user) === '' || trim($_POST['pass']) === '') {
			$war_text .= 'заполнены не все поля!';
		} 

		http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['response' => $war_text]); 
	}


?>