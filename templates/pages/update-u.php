<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


  if(!empty($_POST['unp_id']))
  {
    $cur_id = $_POST['unp_id'];
    $i_user_id = $_POST['i_value'];

	if($_POST["i_value"]) {$i_sql1 =  'user_id = "' . $i_user_id . '" ';}
	
	$i_sql = 'update `'. BEZ_DBPREFIX .'unprocessed` set ' . $i_sql1 . ' WHERE id in (' . $cur_id . '"0")';	

	$db_connect->query($i_sql);	
	//echo $cur_id;
  }
  else
    {
      echo "Что-то пошло не так";
  }



?>