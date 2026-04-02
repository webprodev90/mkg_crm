<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; 
if(!$_SESSION['login_role']) {
	header('Location: /'); 
}
$err = isset($_GET['err'])  ? 'Неверный логин или пароль' : false;

?>
<!-- Begin page -->
<div id="wrapper">

    <!-- Левое меню -->
    <?require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_left.php';?>

    <div class="content-page">
        <!-- Верхнее меню -->
        <?require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_top.php';?>

        <!-- Start Page content -->
        <div class="content">
            <div class="container-fluid">



                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <? if ($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4 or $_SESSION['login_role'] == 11) { ?>
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-6 text-sm-center form-inline">
                                        <div class="form-group mr-2">
                                            <select id="user_names" name="user_names" class="form-control">
                                                <option selected="">Пользователь</option>
                                                <?php
														$sql2 = $db_connect->query('SELECT * FROM `'. BEZ_DBPREFIX .'reg` WHERE ( ' . $_SESSION['login_role'] . ' = "1" AND role in (4, 5)) OR role = 5');
														while ($line2 = $sql2->fetchAssoc()) {
															echo '<option value="' . $line2['id'] . '">' . $line2['name'] . '</option>';
														}
													?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <button id="to_send" class="btn btn-primary"></i>Обновить</button>
                                        </div>
                                    </div>
                                    <div class="col-6 text-sm-center form-inline">
                                        <div class="form-group mr-2">
                                            <form action="/basep/parfbase.php" method="post"
                                                enctype="multipart/form-data">
                                                <input type="file" name="file">
                                                <input type="submit" value="Отправить">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <? } ?>

                            <?

$res1 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_start" AND login_id = "' . $_SESSION['login_id'] . '"');	    						  
$oper_date_s = $res1->fetchAssoc();	

$res2 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_end" AND login_id = "' . $_SESSION['login_id'] . '"');	    
$oper_date_e = $res2->fetchAssoc();		

$res3 = $db_connect->query('SELECT * FROM `bez_reg` WHERE login = "'.$_SESSION['login'].'"');	    
$login_id = $res3->fetchAssoc();		

$res4 = $db_connect->query('SELECT * FROM `st_partner_s` WHERE user_id = "'.$login_id['id'].'"');	    
$partner_id = $res4->fetchAssoc();		
if (!isset($partner_id['id'])){
	$partner_id['id'] = 0;
}



		
        $operator_where = " AND bez_unprocessed_base.user_id = {$_SESSION['login_id']}";
        if(in_array($_SESSION['login_role'], [1, 4])) {
            $operator_where = '';
        }

        $date_filter = "((bez_unprocessed_base.status = 6 AND DATE(bez_unprocessed_base.date_time_status_change) = CURDATE()) OR (bez_unprocessed_base.status = 15 AND bez_sale_request.request_id IS NOT NULL AND bez_sale_request.partner_id <> 65 AND DATE(bez_sale_request.date_time) = CURDATE()) OR (bez_unprocessed_base.status = 10 AND bez_unprocessed_base.source = 'telegram' AND DATE(bez_unprocessed_base.date_create) = CURDATE() AND bez_unprocessed_base.partner <> 65))";
        if(isset($oper_date_s['date_value']) and isset($oper_date_e['date_value'])) {
            $ds = $oper_date_s['date_value'];
            $dend = $oper_date_e['date_value'];
            $date_filter = "((bez_unprocessed_base.status = 6 AND bez_unprocessed_base.date_time_status_change BETWEEN '{$ds}' AND '{$dend}') OR (bez_unprocessed_base.status = 15 AND bez_sale_request.request_id IS NOT NULL AND bez_sale_request.partner_id <> 65 AND bez_sale_request.date_time BETWEEN '{$ds}' AND '{$dend}') OR (bez_unprocessed_base.status = 10 AND bez_unprocessed_base.source = 'telegram' AND bez_unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}' AND bez_unprocessed_base.partner <> 65))";
        }



$res = $db_connect->query('
							SELECT
								bez_unprocessed_base.id,
								bez_unprocessed_base.phone_number,
								bez_unprocessed_base.fio,
								bez_unprocessed_base.city,
								bez_unprocessed_base.vopros,
								bez_unprocessed_base.date_time_status_change
							FROM
								bez_unprocessed_base
							LEFT JOIN bez_sale_request
							ON bez_unprocessed_base.id = bez_sale_request.request_id AND bez_sale_request.partner_id = bez_unprocessed_base.partner
							WHERE
								' . $date_filter 
								  . $operator_where	.'
								AND bez_unprocessed_base.source IS NOT NULL
								AND bez_unprocessed_base.status = 15 OR bez_unprocessed_base.status = 10
								
							limit 10	
						  ');	

$res5 = $db_connect->query("
    SELECT
        bez_reg.id,
                bez_reg.name
            FROM
                bez_reg
            WHERE
                bez_reg.role = 5
    ");

$operators = NULL;
while($row = $res5->fetchAssoc()) {
    $operators[] = $row;
}

echo '<table class="table table-hover m-0 table-actions-bar  nowrap cellspacing="0" width="100%" id="datatable1"><thead><tr>';
		   echo '<th>Id</th>' .
				'<th>Телефон</th>' .
				'<th>Имя</th>' .
				'<th>Город</th>' .
				'<th>Комментарий</th>' .
				'<th>Дата/Время</th>';
echo		'</tr>' .
		'</thead>' .
	 '<tbody>';
 
 
while( $row = $res->fetchAssoc() ){
echo '<tr tr-id="' . $row['id'] . '">';
   echo '<td>' . $row['id'] . '</td>' .
        '<td>' . $row['phone_number'] . '</td>' .
		'<td>' . $row['fio'] . '</td>' .
		'<td>' . $row['city'] . '</td>' .
		'<td>' . $row['vopros'] . '</td>' .
		'<td>' . $row['date_time_status_change'] . '</td>';
echo '</tr>';
}

echo '</tbody>' .
	'</table>';					 
		 
?>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

    </div>


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->


</div>
<!-- END wrapper -->






<?require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'; ?>

<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="/scripts/scriptsbase.js"></script>
