<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

   	if(isset($_POST['partner'])) { 
		$i_partner = $_POST['partner'];
		$i_sql = 'INSERT INTO `'. BEZ_DBPREFIX .'partners_plan` (`partner_id`) VALUES ("' . $i_partner . '")';	
		$db_connect->query($i_sql);	
	}				  
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>