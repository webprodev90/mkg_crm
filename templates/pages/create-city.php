<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    $i_name = $_POST['name2'];
	
	$db_connect->query('INSERT INTO st_city_s ( name_city )
							   VALUES(
										"'. $i_name .'"
										)');

	//echo '<pre>'; print_r($_POST); echo '</pre>';						  
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>