<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


  if(!empty($_POST['unp_id']))
  {
    $cur_id = $_POST['unp_id'];
    $i_val = $_POST['i_value'];
    
	if($_POST["i_value"]) {$i_sql1 =  'status = ' . $i_val;}
	$i_sql = 'update `bez_reg` set ' . $i_sql1 . ' WHERE id in (' . $cur_id . '"0")';	

	$db_connect->query($i_sql);	

  }
  else
    {
      echo "Что-то пошло не так";
  }



?>