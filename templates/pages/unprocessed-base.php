<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; 
if(!$_SESSION['login_role']) {
	header('Location: /'); 
}
$err = isset($_GET['err'])  ? 'Неверный логин или пароль' : false;
if ($_SESSION['login_role'] == "5") {	
	header('Location:'. BEZ_HOST . 'templates/pages/unprocessed-base-1.php?p=10');
	exit;
}
                    
$url = $_SERVER['REQUEST_URI'];
if ($_GET['p']) {
	$param1 = $_GET['p'];
} else {
	$param1 = '10';
}
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
if ($_SESSION['login_role'] == 1 and $_SESSION['debug'] == 'Y') {
	echo '<pre>'; print_r($_SESSION); echo '</pre>';
}

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
$res = $db_connect->query('SELECT `'. BEZ_DBPREFIX .'unprocessed_base`.`id`,
								  `'. BEZ_DBPREFIX .'unprocessed_base`.`fio`,
								  CONCAT("7", `'. BEZ_DBPREFIX .'unprocessed_base`.`phone_number`) as phone_number,
								  `'. BEZ_DBPREFIX .'unprocessed_base`.`vopros`,
								  `'. BEZ_DBPREFIX .'unprocessed_base`.`city`,
								  `'. BEZ_DBPREFIX .'unprocessed_base`.`status`,
								  `'. BEZ_DBPREFIX .'unprocessed_base`.`date_create`,
								  `'. BEZ_DBPREFIX .'unprocessed_base`.`user_id`,
								  `'. BEZ_DBPREFIX .'unprocessed_base`.`timez`,
								  `'. BEZ_DBPREFIX .'reg`.`name`,
								  `st_partner_s`.`partner_name`,
								  `price`.`amount` as amount,
								  `'. BEZ_DBPREFIX .'status`.`status_name`,
								  IFNULL(`'. BEZ_DBPREFIX .'unprocessed_base`.`id_otdel`, 1) as id_otdel
						     FROM `'. BEZ_DBPREFIX .'unprocessed_base` 							 
						     LEFT
						     JOIN `st_partner_s`
						       ON `st_partner_s`.`id` = `'. BEZ_DBPREFIX .'unprocessed_base`.`partner`	
						     LEFT
						     JOIN `price`
						       ON `price`.`user_id` = `'. BEZ_DBPREFIX .'unprocessed_base`.`partner`	
							  AND `price`.`city_id` = (select `st_city_s`.id from `st_city_s` WHERE REPLACE(REPLACE(REPLACE(REPLACE(`'. BEZ_DBPREFIX .'unprocessed_base`.`city`, " ", "" ), " ", "" ), "\r", ""), "\n", "") = REPLACE(REPLACE(REPLACE(REPLACE(`st_city_s`.`name_city`, " ", "" ), " ", "" ), "\r", ""), "\n", "")) 
						     LEFT
						     JOIN `'. BEZ_DBPREFIX .'status`
						       ON `'. BEZ_DBPREFIX .'status`.`status_id` = `'. BEZ_DBPREFIX .'unprocessed_base`.`status`								   
						     LEFT
						     JOIN `'. BEZ_DBPREFIX .'reg`
						       ON `'. BEZ_DBPREFIX .'reg`.`id` = `'. BEZ_DBPREFIX .'unprocessed_base`.`user_id`									   
							WHERE `'. BEZ_DBPREFIX .'unprocessed_base`.`date_create` between "' . $oper_date_s['date_value'] . '" and "' . $oper_date_e['date_value'] . '"
							  AND `'. BEZ_DBPREFIX .'unprocessed_base`.`status` = "' . $param1 . '"
							  AND (`'. BEZ_DBPREFIX .'unprocessed_base`.`source` is NULL or `'. BEZ_DBPREFIX .'unprocessed_base`.`source` = "telegram")
							  AND (('. $_SESSION['login_role'] . ' = 1 OR '. $_SESSION['login_role'] . ' = 11)
							      AND `'. BEZ_DBPREFIX .'unprocessed_base`.`source` is null or `'. BEZ_DBPREFIX .'unprocessed_base`.`source` = "telegram"
								   OR ('.$param1.' in (10,6,12) AND `'. BEZ_DBPREFIX .'unprocessed_base`.`user_id` = "' . $login_id['id'] . '")
								   OR ('.$param1.' in (8,11) AND '. $_SESSION['login_role'] . ' = 5)
								   OR ('.$_SESSION['login_role'] . ' = 5)
								   OR ('.$_SESSION['login_role'] . ' = 4)
								   )	
							   OR (`'. BEZ_DBPREFIX .'unprocessed_base`.`partner` = "' . $partner_id['id'] . '" AND '. $_SESSION['login_role'] . ' = 6 AND `'. BEZ_DBPREFIX .'unprocessed_base`.`date_create` between "' . $oper_date_s['date_value'] . '" and "' . $oper_date_e['date_value'] . '")
						      
							 ORDER BY `'. BEZ_DBPREFIX .'unprocessed_base`.`id` DESC 
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
if ($_SESSION['login_role'] == 1 OR $_SESSION['login_role'] == 41 OR $_SESSION['login_role'] == 11) { 
		   echo '<th><input type="checkbox" id="singleCheckbox1" value="option2" aria-label="Single checkbox Two"></th>'; }		
		   echo '<th>Отдел</th>' .
		        '<th>Id заявки</th>' .
				'<th>Номер</th>' .
				'<th>Имя</th>' .
				'<th>Город</th>' .
				'<th>Комментарий</th>';
if ($_SESSION['login_role'] == 1 OR $_SESSION['login_role'] == 5 OR $_SESSION['login_role'] == 4 OR $_SESSION['login_role'] == 11) { echo '<th>Партнёр</th>'; }						
if ($_SESSION['login_role'] == 6) { echo '<th>Стоимость</th>'; }						
		   echo '<th>Дата поступления заявки</th>'; 
if (($_GET['p'] == 2) or 
    ($_GET['p'] == 3) or 
    ($_GET['p'] == 6)) { echo '<th>Время</th>'; }
if ($_SESSION['login_role'] == 1 OR $_SESSION['login_role'] == 4 OR $_SESSION['login_role'] == 11) { echo '<th>Колл</th>'; }        
if ($_SESSION['login_role'] <> 6) {			
echo			'<th></th>';
}
echo		'</tr>' .
		'</thead>' .
	 '<tbody>';
 
 
while( $row = $res->fetchAssoc() ){
echo '<tr tr-id="' . $row['id'] . '">';
if ($_SESSION['login_role'] == 1  OR $_SESSION['login_role'] == 4 OR $_SESSION['login_role'] == 11) { 
   echo '<td><input type="checkbox" id="singleCheckbox2" name="list" value="' . $row['id'] . '" aria-label="Single checkbox Two"></td>';}
   echo '<td class="otdel-id">' . $row['id_otdel'] . '</td>' .
        '<td>' . $row['id'] . '</td>' .
		'<td>' . $row['phone_number'] . '</td>' .
		'<td>' . $row['fio'] . '</td>' .
		'<td>' . $row['city'] . '</td>';
if ($_SESSION['login_role'] == 6) { echo '<td class="litltext" style="display: block;">' . $row['vopros'] . '</td>'; }	else { echo '<td class="litltext">' . $row['vopros'] . '</td>'; }	
		
		
if ($_SESSION['login_role'] == 1 OR $_SESSION['login_role'] == 5 OR $_SESSION['login_role'] == 4 OR $_SESSION['login_role'] == 11) { echo '<td>' . $row['partner_name'] . '</td>'; }					
if ($_SESSION['login_role'] == 6) { echo '<td>' . $row['amount'] . '</td>'; }					
   echo '<td>' . $row['date_create'] . '</td>';
if (($_GET['p'] == 2) or 
    ($_GET['p'] == 3) or 
    ($_GET['p'] == 6)) { echo '<td>' . $row['timez'] . '</td>'; }
if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4 or $_SESSION['login_role'] == 11) {
    echo '<td class="table-operator" name="operator_id" attr-id="' . $row['user_id'] .'">
        <select class="change-operator">
            <option></option>';
    foreach($operators as $operator) {
        $selected = '';
        if($operator['id'] == $row['user_id']) {
            $selected = ' selected';
        }
        echo '<option ' . $selected . ' value="' . $operator['id'] . '">' . 
                $operator['id'] .
             '</option>';
    }
    echo '</select>
        </td>';
}    					
if ($_SESSION['login_role'] <> 6) {				
echo	'<td><i id="modclick" class="mdi mdi-border-color" style="cursor: pointer;"  data-id="' . $row['id'] . '" data-toggle="modal" data-target="#signup-modal"></i></td>';
}
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



<!-- Signup modal content -->
<div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-center m-b-30">Выбор партнера
                </h2>
                <form class="form-horizontal" action="updatebas-h.php" method="POST">
                    <input type="hidden" id="idval" name="idval">
                    <?/*?>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="fio" name="fio" placeholder="ФИО">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="phone_number" name="phone_number"
                                placeholder="Номер">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="city" name="city" placeholder="Город">
                        </div>
                        <div class="form-group col-md-6">
                            <select id="status" name="status" class="form-control">
                                <option selected="true" disabled="disabled">Статус</option>
                                <?php
										$sql = $db_connect->query('SELECT * FROM `'. BEZ_DBPREFIX .'status` 
										                            WHERE status_id in (6,8,10,11,12)
																	  AND ((status_id not in (10) AND ' . $_SESSION['login_role'] . ' in ("4","5"))
																	   OR ' . $_SESSION['login_role'] . ' = "1")
																	ORDER BY IF(status_id=10, 1, status_id) ASC');
										while ($line = $sql->fetchAssoc()) {
											echo '<option value="' . $line['status_id'] . '">' . $line['status_name'] . '</option>';
										}
									?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="date" class="form-control" id="date_create" name="date_create"
                                placeholder="Дата">
                        </div>

                        <? if (($_GET['p'] == 2) or 
						      ($_GET['p'] == 1) or 
						      ($_GET['p'] == 3) or 
							  ($_GET['p'] == 6))	
						{ ?>
                        <div class="form-group col-md-6">
                            <input type="time" class="form-control" id="timez" name="timez" placeholder="Время">
                        </div>
                        <? } else { ?>
                        <div class="form-group col-md-6">
                            <select id="user_name" name="user_name" class="form-control">
                                <option selected="true" disabled="disabled">Пользователь</option>
                                <?php
										$sql2 = $db_connect->query('SELECT * FROM `'. BEZ_DBPREFIX .'reg` WHERE ( ' . $_SESSION['login_role'] . ' = "1" AND role in (4, 5)) OR role = 5');
										while ($line2 = $sql2->fetchAssoc()) {
											echo '<option value="' . $line2['id'] . '">' . $line2['name'] . '</option>';
										}
									?>
                            </select>
                        </div>
                        <? } ?>
                    </div>
                    <div class="form-row">
                        <? if ($_SESSION['login_role'] == 1)	
						{ ?>
                        <div class="form-group col-md-6">
                            <select id="partner" name="partner" class="form-control">
                                <option selected="true" disabled="disabled">Партнер</option>
                                <?php
										$sql3 = $db_connect->query('SELECT * FROM `st_partner_s`');
										while ($line3 = $sql3->fetchAssoc()) {
											echo '<option value="' . $line3['id'] . '">' . $line3['partner_name'] . '</option>';
										}
									?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <select id="user_name" name="user_name" class="form-control">
                                <option selected="true" disabled="disabled">Пользователь</option>
                                <?php
										$sql2 = $db_connect->query('SELECT * FROM `'. BEZ_DBPREFIX .'reg` WHERE ( ' . $_SESSION['login_role'] . ' = "1" AND role in (4, 5)) OR role = 5');
										while ($line2 = $sql2->fetchAssoc()) {
											echo '<option value="' . $line2['id'] . '">' . $line2['name'] . '</option>';
										}
									?>
                            </select>
                        </div>
                        <? } else { ?>
                        <div class="form-group col-md-12">
                            <select id="partner" name="partner" class="form-control">
                                <option selected="true" disabled="disabled">Партнер</option>
                                <?php
										$sql3 = $db_connect->query('SELECT * FROM `st_partner_s`');
										while ($line3 = $sql3->fetchAssoc()) {
											echo '<option value="' . $line3['id'] . '">' . $line3['partner_name'] . '</option>';
										}
									?>
                            </select>
                        </div>
                        <? } ?>
                        <?*/?>
                        <div class="form-group col-md-12">
                            <select id="partner" name="partner" class="form-control">
                                <option selected="true" disabled="disabled">Партнер</option>
                                <?php
										$sql3 = $db_connect->query('SELECT * FROM `st_partner_s`');
										while ($line3 = $sql3->fetchAssoc()) {
											echo '<option value="' . $line3['id'] . '">' . $line3['partner_name'] . '</option>';
										}									
									
									
										/*$sql3 = $db_connect->query('SELECT par.id as id, par.partner_name as partner_name FROM `st_partner_s` as par WHERE par.user_id = 0 UNION ALL 
																	SELECT par.id as id, par.partner_name as partner_name FROM `st_partner_s` as par JOIN `balance` as bal ON par.user_id = bal.user_id AND bal.amount > 0');
										while ($line3 = $sql3->fetchAssoc()) {
											echo '<option value="' . $line3['id'] . '">' . $line3['partner_name'] . '</option>';
										}*/
									?>
                            </select>
                        </div>

                    </div>
                    <!--					
					<div class="form-row">
                        <div class="form-group col-12">
							<textarea class="form-control" rows="5" id="vopros" name="vopros" placeholder="Комментарий кольщика по вопросу клиента."></textarea>
						</div>						
					</div>	
-->
                    <div class="form-group account-btn text-center m-t-10">
                        <div class="col-12">
                            <button id="savebut" class="btn w-lg btn-rounded btn-primary waves-effect waves-light"
                                type="submit">Отправить</button>
                        </div>
                    </div>

                </form>


            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->







<?require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'; ?>

<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="/scripts/scriptsbase.js"></script>
