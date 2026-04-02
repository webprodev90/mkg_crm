<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config2.php';
//require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd2.php';

function clean_phone_number($phone_number) {
	$cleaned_phone_number = preg_replace('/\D/', '', $phone_number);

	if(preg_match('/^[87]/', $cleaned_phone_number)) {
		$cleaned_phone_number = substr($cleaned_phone_number, 1);
	}
	
	$cleaned_phone_number = substr($cleaned_phone_number, -10);

	return '8' . $cleaned_phone_number;
}

  if(!empty($_POST['phone_number'])) {
    $phone_number = clean_phone_number($_POST['phone_number']);

	$res = $db_connect2->query('SELECT * FROM app_entity_25 WHERE field_211 = "' . $phone_number . '" AND field_214 <> "" AND field_214 IS NOT NULL ORDER BY id DESC LIMIT 1;');	  
		
	if ($res->getNumRows() > 0) {
		$row = $res->fetchAssoc();
		header('Content-Type: application/json');
		ob_end_clean(); 
		echo json_encode($row, JSON_UNESCAPED_UNICODE);
	} else {
		echo 0;
	}
	
  } else {
      echo "Что-то пошло не так";
  }



?>