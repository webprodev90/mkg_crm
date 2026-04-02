<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

//SELECT * FROM `bez_unprocessed_base` WHERE `bez_unprocessed_base`.`id` = 762 ORDER BY `bez_unprocessed_base`.`id` DESC;
    $cur_id = 762;


	$res = $db_connect->query('SELECT * FROM `'. BEZ_DBPREFIX .'unprocessed_base` WHERE id = "' . $cur_id . '"');	 
	$row = $res->fetchAssoc();	
	$res2 = $db_connect->query('SELECT * FROM `st_partner_s` WHERE id = "' . $row["partner"] . '"');	   
	$row2 = $res2->fetchAssoc();
	

	$res0 = $db_connect->query('SELECT * FROM `bez_reg` WHERE id = "' . $row2["user_id"] . '"');	   
	$role = $res0->fetchAssoc();	



if ( $role["role"] == '6') {	
	$res3 = $db_connect->query('SELECT * FROM `balance` WHERE user_id = "' . $row2["user_id"] . '"');	   
	$balance = $res3->fetchAssoc();	

	$res4 = $db_connect->query('SELECT * FROM `st_city_s` WHERE name_city = "' . trim($row["city"]) . '"');	   
	$city = $res4->fetchAssoc();	
	
	//echo '</pre>'; print_r($city); echo '</pre>';

	$res5 = $db_connect->query('SELECT * FROM `price` WHERE user_id = "' . $row2["id"] . '" AND city_id = "' . $city["id"] . '"');	   
	$price = $res5->fetchAssoc();	
	
	$bal = $balance["amount"] - $price["amount"];
	
	$v_sql = 'UPDATE `balance` SET amount = '. $bal .'  WHERE user_id = "' . $row2["user_id"] . '"';	
	$db_connect->query($v_sql);	
}

	
/*
    $i_text = $i_sql1 . $i_sql2 . $i_sql3 . $i_sql4 . $i_sql5 . $i_sql6 . $i_sql7 . $i_sql8 . $i_sql9 . 'id = "' . $cur_id;
	$i_text = str_replace(',',';',$i_text);
	$i_text = str_replace('"','',$i_text);
    $i_value = 'update';

	$db_connect->query('INSERT INTO logs ( row_change_time, value, text, user_id) VALUES ("' .$i_row_change_time . '", "' .$i_value . '", "' .$i_text . '", "' .$i_user_id . '")');	    
*/
	//echo '<pre>'; print_r($_POST); echo '</pre>';						  




?>