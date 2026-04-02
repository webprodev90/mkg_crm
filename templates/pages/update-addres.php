<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

  if(!empty($_POST['idval2']))
  {
    $cur_id = $_POST['idval2'];
    $i_user_id = $_POST['name'];

	if($_POST["name"]) {$i_sql1 =  'name_addres = "' . $i_user_id . '" ';}
	
	$i_sql = 'update `st_addres_s` set ' . $i_sql1 . ' WHERE id in ("' . $cur_id . '","0")';	

	$db_connect->query($i_sql);	
	
	header('Location:'. $_SERVER['HTTP_REFERER']);
  }
  else
    {
      echo "Что-то пошло не так";
  }



?>