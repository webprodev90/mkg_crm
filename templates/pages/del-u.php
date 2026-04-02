<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


if(!empty($_POST['unp_id2'])) {
  $cur_id = $_POST['unp_id2'];

	$db_connect->query('DELETE FROM bez_reg WHERE id = ' . $cur_id);	    
	$db_connect->query('DELETE FROM settings WHERE login_id = ' . $cur_id);	

  $results = [];
  $res = $db_connect->query("
    SELECT * FROM user_atc WHERE user_id = {$cur_id} AND actual_end_date IS NULL;
  ");

  while($row = $res->fetchAssoc()) {
    $results[] = $row;
  }

  if(count($results) !== 0) {
    foreach ($results as $result) {
      $query_request = "
        UPDATE user_atc SET actual_end_date = NOW() WHERE id = {$result['id']}
      ";         
      $db_connect->query($query_request);                 
    }                     
  }    
	  
} else {
  echo "Что-то пошло не так";
}



?>