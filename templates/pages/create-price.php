<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    $i_part = $_POST['part1'];
    $i_city = $_POST['city1'];
    $i_price = $_POST['price2'];
	
	$db_connect->query('INSERT INTO price ( user_id, city_id, amount )
							   VALUES(
										"'. $i_part .'",
										"'. $i_city .'",
										"'. $i_price .'"
										)');

	//echo '<pre>'; print_r($_POST); echo '</pre>';						  
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>