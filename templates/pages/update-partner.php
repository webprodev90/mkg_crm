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
    $i_email = $_POST['email'];
    $i_login = $_POST['login'];
    $i_pass = $_POST['pass'];

	if($_POST["name"]) {$i_sql1 =  'partner_name = "' . $i_user_id . '", ';}
	if($_POST["email"]) {$i_sql2 =  'email = "' . $i_email . '", ';}
	if($_POST["login"]) {$i_sql3 =  'login = "' . $i_login . '", ';}
	if($_POST["pass"]) {$i_sql4 =  'pass = "' . $i_pass . '" ';}
	
	$i_sql = 'update `st_partner_s` set ' . $i_sql1 . $i_sql2 . $i_sql3 . $i_sql4 . ' WHERE id in ("' . $cur_id . '","0")';	

	$db_connect->query($i_sql);	

	$i_passmd5 = md5(md5(trim($i_pass)));
	$ch = curl_init();
	$queryURL = 'https://lk.mkggroup.ru/admin/api/rest.php'
		. '?param=updatepartneruser'
		. '&key=fa9sgwl456gjs9g456dsjlgjd456sjg45345789lsdlgs'
		. '&login=' . curl_escape($ch, $i_login)
		. '&partner_id=' . curl_escape($ch, $cur_id)
		. '&password=' . curl_escape($ch, $i_passmd5);

	curl_setopt($ch, CURLOPT_URL, $queryURL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	
	header('Location:'. $_SERVER['HTTP_REFERER']);
  }
  else
    {
      echo "Что-то пошло не так";
  }



?>