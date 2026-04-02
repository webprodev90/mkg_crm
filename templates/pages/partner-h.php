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
    $cur_id3 = $_POST['unp_id3'];
	$json = array();
/*	

	

$res1 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_start" AND login_id = "' . $_SESSION['login_id'] . '"');	    						  
$oper_date_s = $res1->fetchAssoc();	

$res2 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_end" AND login_id = "' . $_SESSION['login_id'] . '"');	    
$oper_date_e = $res2->fetchAssoc();	
*/

	
	$res = $db_connect->query("SELECT DISTINCT bez_unprocessed_base.id,
									        bez_unprocessed_base.fio,
									        CONCAT('7', bez_unprocessed_base.phone_number) AS phone_number,
									        bez_unprocessed_base.vopros,
									        bez_unprocessed_base.city
									FROM bez_sale_request bsr1
									JOIN bez_unprocessed_base 
									ON bez_unprocessed_base.id = bsr1.request_id
									WHERE bsr1.date_time BETWEEN '{$cur_id2} 00:00:00' AND '{$cur_id3} 23:59:59'         
									AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr1.partner_id <> 65 and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
									AND bsr1.partner_id = {$cur_id}
									UNION ALL 
									SELECT bez_unprocessed_base.id,
									     bez_unprocessed_base.fio,
									     bez_unprocessed_base.phone_number,
									     bez_unprocessed_base.vopros,
									     bez_unprocessed_base.city
									FROM bez_unprocessed_base 
									WHERE bez_unprocessed_base.is_ship = '1' AND DATE_FORMAT(bez_unprocessed_base.date_create, '%Y-%m-%d') between DATE_FORMAT('{$cur_id2}', '%Y-%m-%d') and DATE_FORMAT('{$cur_id3}', '%Y-%m-%d') AND bez_unprocessed_base.source = 'telegram' AND bez_unprocessed_base.partner = {$cur_id} AND bez_unprocessed_base.user_id <> ''
									ORDER BY id ASC;
								");    

		$json['html'] = '<table class="table table-hover m-0 table-actions-bar  nowrap cellspacing="0" width="100%" id="datatable1"><thead><tr>' .	
					'<th>ID</th>' .	
					'<th>ФИО</th>' .	
					'<th>Телефон</th>' .	
					'<th>Город</th>' .	
				    '<th>Вопрос</th>' .	
				'</tr>' .	                                                                                    
			'</thead>' .	
		 '<tbody>';
	 
 
	while( $row = $res->fetchAssoc() ){
		$json['html'] .= 
			'<tr>' .
			   '<td>' . $row['id'] . '</td>' .
			   '<td>' . $row['fio'] . '</td>' .	 				
			   '<td>' . $row['phone_number'] . '</td>' .					
			   '<td>' . $row['vopros'] . '</td>' .					
			   '<td>' . $row['city'] . '</td>' .					
			'</tr>';
	}

$json['html'] .= '</tbody></table>';	

//header('Content-Type: application/json');
echo json_encode( $json, JSON_UNESCAPED_UNICODE);
  }
  else
    {
      echo "Что-то пошло не так";
  }


?>