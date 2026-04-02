<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


	$res3 = $db_connect->query('SELECT salt FROM `bez_reg` WHERE id = "'.$_POST['idval2'].'"');	    
	$result3 = $res3->fetchAssoc();	
	$salt = $result3['salt'];	

    $cur_id = $_POST['idval2'];
    $i_login = $_POST['login'];
	$i_pass = md5(md5($_POST['password']).$salt);	
    $i_status = $_POST['status'];
    $i_role = $_POST['role'];
    $i_name = $_POST['name'];
    $i_user = $_POST['user'];
    $i_address_id = $_POST['address'];
    $id_otdel = $_POST['id_otdel'];
    $id_atc = $_POST['id_atc'];
    $i_user_id = $_POST['user_name'];

	if($_POST["login"]) {$i_sql1 = 'login  = "' . $i_login . '", ';}
	if($_POST["password"]) {
        $i_sql2 = 'pass  = "' . $i_pass . '", ';
        $i_sql10 = 'view_password  = "' . $_POST["password"] . '.' . $i_pass . '", ';
    }
	if($_POST["status"]) {$i_sql3 =  'status  = "' . $i_status . '", ';}
	if($_POST["role"]) {$i_sql4 =  'role  = "' . $i_role . '", ';}
	if($_POST["name"]) {$i_sql5 =  'name  = "' . $i_name . '", ';}
	if($_POST["user"]) {$i_sql6 =  'user  = "' . $i_user . '", ';}
	if($_POST["address"]) {$i_sql7 =  'address_id = "' . $i_address_id . '", ';}
	if($_POST["id_otdel"]) {$i_sql8 =  'id_otdel = "' . $id_otdel . '", ';}
	if(isset($_POST["id_atc"])) {

		$i_sql9 =  'id_atc = "' . $id_atc . '", ';

		$results4 = [];
        $res4 = $db_connect->query("
            SELECT * FROM user_atc WHERE (user_id = {$cur_id} OR atc_id = {$id_atc}) AND actual_end_date IS NULL;
        ");

        while($row = $res4->fetchAssoc()) {
            $results4[] = $row;
        }

        if(count($results4) !== 0) {
            foreach ($results4 as $result4) {
                $query_request2 = "
                    UPDATE user_atc SET actual_end_date = NOW() WHERE id = {$result4['id']}
                ";         
                $db_connect->query($query_request2);                 
            }                     
        }

        $query_request3 = "
            INSERT INTO user_atc (user_id, atc_id, actual_start_date) VALUES ('{$cur_id}', '{$id_atc}', NOW());
        ";

        $db_connect->query($query_request3);

	}

	$i_sql = 'UPDATE `'. BEZ_DBPREFIX .'reg` SET ' . $i_sql1 . $i_sql2 . $i_sql3 . $i_sql4 . $i_sql5 . $i_sql6 . $i_sql7 . $i_sql8 . $i_sql9 . $i_sql10 . 'id = "' . $cur_id . '" WHERE id = ' . $cur_id;	
	$db_connect->query($i_sql);	

    $i_text = $i_sql1 . $i_sql2 . $i_sql3 . $i_sql4 . $i_sql5 . $i_sql6 . $i_sql7 . $i_sql8 . $i_sql9 . $i_sql10 . 'id = "' . $cur_id;
	$i_text = str_replace(',',';',$i_text);
	$i_text = str_replace('"','',$i_text);
    $i_value = 'update';

	//$db_connect->query('INSERT INTO logs ( row_change_time, value, text, user_id) VALUES ("' .$i_row_change_time . '", "' .$i_value . '", "' .$i_text . '", "' .$i_user_id . '")');	    
				  
	header('Location:'. $_SERVER['HTTP_REFERER']);



?>