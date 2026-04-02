<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';
	
	$loginid = $_SESSION['login_id'];
	
	$_SESSION['oper_date'] = $_POST['dateselector'];
	$date_create_start = substr($_SESSION['oper_date'], 0, 10);
	$date_create_end = substr($_SESSION['oper_date'], 12, 20);
	
	$_SESSION['oper_date_start'] = date('Y-m-d', strtotime(strtr($date_create_start, '/', '-')));
	$_SESSION['oper_date_end'] = date('Y-m-d', strtotime(strtr($date_create_end, '/', '-')));
  

   
	$oper_date_start = trim(preg_replace('/[\t\n\r\s]+/', ' ', $_SESSION['oper_date_start']));
	$oper_date_end = trim(preg_replace('/[\t\n\r\s]+/', ' ', $_SESSION['oper_date_end']));	
	
  if(!empty($_POST['dateselector']))
  {
 
	$db_connect->query('update `settings` 
								 set  date_value = "' . $oper_date_start . '" 
								WHERE name_value = "oper_date_start"
								  AND login_id = "' . $loginid . '" '
							
							  );	    
	$db_connect->query('update `settings` 
								 set  date_value = "' . $oper_date_end . '" 
								WHERE name_value = "oper_date_end"
								  AND login_id = "' . $loginid . '" '
							
							  );



							  
	//$row = $res->fetchAssoc();
  


  }
  else
    {
      echo "Что-то пошло не так";
  }



	
	//print_r($_SESSION['oper_date_start']);//die();
	//print_r($_SESSION['oper_date_end']);//die();
    header('Location:'. $_SERVER['HTTP_REFERER']);






?>