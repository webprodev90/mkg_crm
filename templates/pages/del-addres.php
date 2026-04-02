<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


  if(!empty($_POST['unp_id2']))
  {
    $cur_id = $_POST['unp_id2'];

	$db_connect->query('DELETE FROM st_addres_s WHERE id = ' . $cur_id);	    
	  
  }
  else
    {
      echo "Что-то пошло не так";
  }



?>