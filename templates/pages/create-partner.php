<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    $i_name = $_POST['name2'];
    $i_email = $_POST['email2'];
	
	$db_connect->query('INSERT INTO st_partner_s ( partner_name, email )
							   VALUES(
										"'. $i_name .'",
										"'. $i_email .'"
										)');

	$res = $db_connect->query('SELECT max(id) as partner_id FROM st_partner_s');	
	while( $row = $res->fetchAssoc() ){	
		$partner_id = $row['partner_id'];
	}

	$i_login = translit($i_name).$partner_id;
	$i_pass = generator_password(8);

	$db_connect->query('UPDATE st_partner_s 
						   SET login = "'. $i_login .'",
							   pass = "'. $i_pass .'"
						 WHERE id = "'. $partner_id .'"					
					   ');

	$i_passmd5 = md5(md5(trim($i_pass)));
	$i_partner_id = $partner_id;
	$ch = curl_init();

	$queryURL = 'https://lk.mkggroup.ru/admin/api/rest.php'
		. '?param=addpartneruser'
		. '&key=fa9sgwl456gjs9g456dsjlgjd456sjg45345789lsdlgs'
		. '&login=' . curl_escape($ch, $i_login)
		. '&partner_id=' . curl_escape($ch, $i_partner_id)
		. '&password=' . curl_escape($ch, $i_passmd5);

	curl_setopt($ch, CURLOPT_URL, $queryURL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);


	//echo '<pre>'; print_r($_POST); echo '</pre>';						  
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>