<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; 
if(!$_SESSION['login_role']) {
	header('Location: /'); 
}
$err = isset($_GET['err'])  ? 'Неверный логин или пароль' : false;
$url = $_SERVER['REQUEST_URI'];
$param1 = '4';
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
	<? if ($_SESSION['login_role'] == 1) { ?>						
							<div class="mb-3">
								<div class="row">
									<div class="col-12 text-sm-center form-inline">
										<div class="form-group mr-2">
											<select id="user_names" name="user_names" class="form-control">
												<option selected="">Пользователь</option>
													<?php
														$sql2 = $db_connect->query('SELECT * FROM `'. BEZ_DBPREFIX .'reg` WHERE role = 2');
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

$res = $db_connect->query('SELECT `'. BEZ_DBPREFIX .'unprocessed`.`id`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`fio`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`phone_number`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`vopros`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`address`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`city`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`status`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`date_create`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`user_id`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`timez`,
								  `'. BEZ_DBPREFIX .'reg`.`name`,
								  `'. BEZ_DBPREFIX .'status`.`status_name`
	
						     FROM `'. BEZ_DBPREFIX .'unprocessed` 
						     LEFT
						     JOIN `'. BEZ_DBPREFIX .'status`
						       ON `'. BEZ_DBPREFIX .'status`.`status_id` = `'. BEZ_DBPREFIX .'unprocessed`.`status`			
						     LEFT
						     JOIN `'. BEZ_DBPREFIX .'reg`
						       ON `'. BEZ_DBPREFIX .'reg`.`id` = `'. BEZ_DBPREFIX .'unprocessed`.`user_id`									   
							WHERE `'. BEZ_DBPREFIX .'unprocessed`.`date_create` between "' . $oper_date_s['date_value'] . '" and "' . $oper_date_e['date_value'] . '"
							  AND `'. BEZ_DBPREFIX .'unprocessed`.`status` = "' . $param1 . '"
							  AND ('. $_SESSION['login_role'] . '= 1
							   OR `'. BEZ_DBPREFIX .'unprocessed`.`user_id` = "' . $login_id['id'] . '")
						  ');	


echo '<table class="table table-hover m-0 table-actions-bar dt-responsive nowrap" cellspacing="0" width="100%" id="datatable1">' .
		'<thead>' .
			'<tr>' .
				'<th><input type="checkbox" id="singleCheckbox1" value="option2" aria-label="Single checkbox Two"></th>' .
				'<th>Id заявки</th>' .
				'<th>ФИО</th>' .
				'<th>Номер</th>' .
				'<th>Вопрос</th>' .
				'<th>Адрес</th>' .
				'<th>Статус</th>' .
				'<th>Дата поступления заявки</th>'; 
	
if (substr_count($url, 'unprocessed2.php') > 0 or 
	substr_count($url, 'unprocessed3.php') > 0 or 
	substr_count($url, 'unprocessed6.php') > 0) { echo '<th>Время</th>'; }
if ($_SESSION['login_role'] == 1) { echo '<th>Пользователь</th>'; }		
		
echo			'<th></th>' .
			'</tr>' .
		'</thead>' .
	 '<tbody>';
 
 
while( $row = $res->fetchAssoc() ){
echo '<tr>' .
		'<td><input type="checkbox" id="singleCheckbox2" name="list" value="' . $row['id'] . '" aria-label="Single checkbox Two"></td>' .
		'<td>' . $row['id'] . '</td>' .
		'<td>' . $row['fio'] . '</td>' .
		'<td>' . $row['phone_number'] . '</td>' .
		'<td>' . $row['vopros'] . '</td>' .
		'<td>' . $row['address'] . '</td>' .
		'<td>' . $row['status_name'] . '</td>' .
		'<td>' . $row['date_create'] . '</td>';
if (substr_count($url, 'unprocessed2.php') > 0 or 
	substr_count($url, 'unprocessed3.php') > 0 or 
	substr_count($url, 'unprocessed6.php') > 0) { echo '<td>' . $row['timez'] . '</td>'; }					
if ($_SESSION['login_role'] == 1) { echo '<td>' . $row['name'] . '</td>'; }					
echo	'<td><i id="modclick" class="mdi mdi-border-color" style="cursor: pointer;"  data-id="' . $row['id'] . '" data-toggle="modal" data-target="#signup-modal"></i></td>' .
	 '</tr>';
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
<div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-body">
				<h2 class="text-center m-b-30">Редактирование записи
				</h2>
				<form class="form-horizontal" action="update-h.php" method="POST">
					<input type="hidden" id="idval" name="idval">
					<div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="fio" name="fio" placeholder="ФИО">
						</div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Номер">
						</div>						
					</div>				   
					<div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="city" name="city" placeholder="Город">
						</div>
                        <div class="form-group col-md-6">
							<select id="status" name="status" class="form-control">
								<option selected="">Статус</option>
									<?php
										$sql = $db_connect->query('SELECT * FROM `'. BEZ_DBPREFIX .'status` 
										                            WHERE (status_id not in (4,9) AND ' . $_SESSION['login_role'] . ' = "2")
																	   OR ' . $_SESSION['login_role'] . ' = "1"');
										while ($line = $sql->fetchAssoc()) {
											echo '<option value="' . $line['status_id'] . '">' . $line['status_name'] . '</option>';
										}
									?>
                            </select>
						</div>						
					</div>	
					<div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="date" class="form-control" id="date_create" name="date_create" placeholder="Дата">
						</div>	

                        <? if (substr_count($url, 'unprocessed2.php') > 0 or 
						       substr_count($url, 'unprocessed3.php') > 0 or 
							   substr_count($url, 'unprocessed6.php') > 0)	
						{ ?>			
						<div class="form-group col-md-6">
							<input type="time" class="form-control" id="timez" name="timez" placeholder="Время">
						</div>  
						<? } else { ?>
						<div class="form-group col-md-6">						
							<select id="user_name" name="user_name" class="form-control">
								<option selected="">Пользователь</option>
									<?php
										$sql2 = $db_connect->query('SELECT * FROM `'. BEZ_DBPREFIX .'reg` WHERE role = 2');
										while ($line2 = $sql2->fetchAssoc()) {
											echo '<option value="' . $line2['id'] . '">' . $line2['name'] . '</option>';
										}
									?>
							</select>
						</div>		
						<? } ?>
					</div>	
					<div class="form-row">
                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" id="address" name="address" placeholder="Адрес">
						</div>
					</div>						
					<div class="form-row">
                        <div class="form-group col-12">
							<textarea class="form-control" rows="5" id="vopros" name="vopros" placeholder="Комментарий кольщика по вопросу клиента."></textarea>
						</div>						
					</div>	
					
					<div class="form-group account-btn text-center m-t-10">
						<div class="col-12">
							<button id="savebut" class="btn w-lg btn-rounded btn-primary waves-effect waves-light" type="submit">Сохранить</button>
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
<script src="/scripts/scripts.js"></script>