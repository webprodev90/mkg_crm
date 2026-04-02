<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


  if(!empty($_POST['unp_id']))
  {
  
    $cur_id = $_POST['unp_id'];
    $cur_id2 = $_POST['unp_id2'];

	$db_connect->query('DELETE FROM bez_unprocessed_base WHERE id between ' . $cur_id . ' and ' . $cur_id2);

	header('Location:'. $_SERVER['HTTP_REFERER']);  
  }
  else
  {
      echo "Что-то пошло не так";
  }



?>