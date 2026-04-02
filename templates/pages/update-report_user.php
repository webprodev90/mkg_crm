<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

  if(!empty($_POST['idval2']))
  {
    $cur_id = $_POST['idval2'];
    $user_id = $_POST['user_id'];
    $i_work_hours = $_POST['work_hours'];
    $date_start = $_POST['date_start']; 
    $date_end = $_POST['date_end']; 
	
	if($cur_id == 999999999) {
		$db_connect->query('INSERT INTO st_report_user (  user_id, work_hours, row_change_time )
							   VALUES(
										"'. $user_id .'",
										"'. $i_work_hours .'",
										"'. $date_start .'"
										)');
	}


	if($cur_id !== 999999999) {
		$i_sql1 =  'work_hours = "' . $i_work_hours . '" ';
		$i_sql = 'update st_report_user set ' . $i_sql1 . ' WHERE id in ("' . $cur_id . '","0")';	
		$db_connect->query($i_sql);	
	}

		
	header('Location:'. $_SERVER['HTTP_REFERER']);
  }
  else
    {
      echo "Что-то пошло не так";
  }



?>