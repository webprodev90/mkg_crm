<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

  if(!empty($_POST['idval2']))
  {
    $cur_id = $_POST['idval2'];
    $i_user_id = $_POST['part1'];
    $i_city = $_POST['city1'];
    $i_price = $_POST['price2'];

	if($_POST["part1"]) {$i_sql1 =  'user_id = "' . $i_user_id . '", ';}
	if($_POST["city1"]) {$i_sql2 =  'city_id = "' . $i_city . '", ';}
	if($_POST["price2"]) {$i_sql3 =  'amount = "' . $i_price . '" ';}
	
	$i_sql = 'update `price` set ' . $i_sql1 . $i_sql2 . $i_sql3 . ' WHERE id in ("' . $cur_id . '","0")';	

	$db_connect->query($i_sql);	
	
	header('Location:'. $_SERVER['HTTP_REFERER']);
  }
  else
    {
      echo "Что-то пошло не так";
  }



?>