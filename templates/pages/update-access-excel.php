<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


  if(isset($_POST['value_access']))
  {
    $cur_id = $_POST['value_access'];

	  $db_connect->query('UPDATE options SET number_value = '. $cur_id .' WHERE param_name = "EXCELON"');	      
	  
  }
  else
    {
      echo "Что-то пошло не так";
  }



?>