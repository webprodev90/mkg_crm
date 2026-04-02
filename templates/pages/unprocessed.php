<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; 
if(!$_SESSION['login_role']) {
	header('Location: /'); 
}
$err = isset($_GET['err'])  ? 'Неверный логин или пароль' : false;
$url = $_SERVER['REQUEST_URI'];
if ($_GET['p']) {
	$param1 = $_GET['p'];
} else {
	$param1 = '1';
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
if (/*$_SESSION['login_role'] == 1 and*/ $_SESSION['debug'] == 'Y') {
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
								  `st_addres_s`.`name_addres`,
								  `st_city_s`.`name_city`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`status`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`date_create`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`date_zagruzki`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`user_id`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`timez`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`is_dubl`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`istochnik`,
								  `'. BEZ_DBPREFIX .'unprocessed`.`date_time_status_change`,									  
								  `'. BEZ_DBPREFIX .'reg`.`name`,
								  `'. BEZ_DBPREFIX .'status`.`status_name`
	
						     FROM `'. BEZ_DBPREFIX .'unprocessed` 
						     LEFT
						     JOIN `st_addres_s`
						       ON `st_addres_s`.`id` = `'. BEZ_DBPREFIX .'unprocessed`.`address`								 
						     LEFT
						     JOIN `st_city_s`
						       ON `st_city_s`.`id` = `'. BEZ_DBPREFIX .'unprocessed`.`city`								 
						     LEFT
						     JOIN `'. BEZ_DBPREFIX .'status`
						       ON `'. BEZ_DBPREFIX .'status`.`status_id` = `'. BEZ_DBPREFIX .'unprocessed`.`status`								   
						     LEFT
						     JOIN `'. BEZ_DBPREFIX .'reg`
						       ON `'. BEZ_DBPREFIX .'reg`.`id` = `'. BEZ_DBPREFIX .'unprocessed`.`user_id`									   
							WHERE ((`'. BEZ_DBPREFIX .'unprocessed`.`date_create` between "' . $oper_date_s['date_value'] . '" and "' . $oper_date_e['date_value'] . '" AND (' . $param1 . ' = "1" OR ' . $param1 . ' = "99")) 
									OR
							        (`'. BEZ_DBPREFIX .'unprocessed`.`date_time_status_change` IS NOT NULL and DATE(`'. BEZ_DBPREFIX .'unprocessed`.`date_time_status_change`) between "' . $oper_date_s['date_value'] . '" and "' . $oper_date_e['date_value'] . '" AND (' . $param1 . ' <> "1" AND ' . $param1 . ' <> "99"))
								  )
							  AND ( ( ' . $param1 . ' = "99")  							  
									   OR
							        (( ' . $param1 . ' <> "99")  AND (`'. BEZ_DBPREFIX .'unprocessed`.`status` = "' . $param1 . '"))
							      )
							  AND
									   (
										  ('. $_SESSION['login_role'] . ' in (1,3)
											   OR 
										   `'. BEZ_DBPREFIX .'unprocessed`.`user_id` = "' . $login_id['id'] . '"
										   )										 
									   )						   
									
							ORDER BY `'. BEZ_DBPREFIX .'unprocessed`.`id`DESC	
						  ');	


echo '<table class="table table-hover m-0 table-actions-bar nowrap" cellspacing="0" width="100%" id="datatable1"><thead><tr>';
if ($_SESSION['login_role'] == 1) { 
		   echo '<th><input type="checkbox" id="singleCheckbox1" value="option2" aria-label="Single checkbox Two"></th>'; }		
		   echo '<th>Id заявки</th>' .   
				'<th>ФИО</th>' .
				'<th>Номер</th>' .
				'<th>Вопрос</th>' .
				'<th>Адрес</th>' .
				'<th>Статус</th>' .
				'<th>Дата поступления заявки</th>'; 
if (($_GET['p'] == 2) or 
    ($_GET['p'] == 3) or 
    ($_GET['p'] == 6)) { echo '<th>Время</th>'; }
if ($_SESSION['login_role'] == 1) { echo '<th>Пользователь</th>'; }		
		
echo			'<th></th>' .
				'<th></th>' .
			'</tr>' .
		'</thead>' .
	 '<tbody>';
 
 
while( $row = $res->fetchAssoc() ){

if (/*$param1 == '99' and */$row['is_dubl'] == 'Y') {echo '<tr style="background-color: pink;">';}
elseif (/*$param1 == '99' and */($row['status'] == '2' or  $row['status'] == '3')) {echo '<tr style="background-color: Yellow;">';} 
elseif (/*$param1 == '99' and*/ $row['status'] == '4') {echo '<tr style="background-color: lime;">';}
elseif (/*$param1 == '99' and*/ $row['status'] == '5') {echo '<tr style="background-color: Red;">';} 
elseif (/*$param1 == '99' and */$row['status'] == '6') {echo '<tr style="background-color: #78adf0;">';}
elseif (/*$param1 == '99' and */ $row['status'] == '7') {echo '<tr style="background-color: Orange;">';}
elseif (/*$param1 == '99' and */$row['status'] == '8') {echo '<tr style="background-color: Silver;">';}
elseif (/*$param1 == '99' and */$row['status'] == '9') {echo '<tr style="background-color: Gray;">';} 

else {echo '<tr>';}

   if ($_SESSION['login_role'] == 6 and $row['istochnik'] <> 'crm') { $i_phones_p = $row['phone_number'];}
   elseif ($_SESSION['login_role'] <> 6) { $i_phones_p = $row['phone_number'];}
   else {$i_phones_p = '';}
   
   if ($_SESSION['login_role'] == 6) { $i_litltext_s = 'litltext1';}
   else {$i_litltext_s = 'litltext';}
   
if ($_SESSION['login_role'] == 1) { 


   echo '<td><input type="checkbox" id="singleCheckbox2" name="list" value="' . $row['id'] . '" aria-label="Single checkbox Two"></td>';}
   echo '<td>' . $row['id'] . '</td>' .  
		'<td>' . $row['fio'] . '</td>' .
		'<td>' . $i_phones_p . '</td>' .
		'<td class="' . $i_litltext_s . '">' . $row['vopros'] . '</td>' .
		'<td>' . $row['name_addres'] . '</td>' .
		'<td>' . $row['status_name'] . '</td>' .
		'<td>' . $row['date_create'] . '</td>';
if (($_GET['p'] == 2) or 
    ($_GET['p'] == 3) or 
    ($_GET['p'] == 6)) { echo '<td>' . $row['timez'] . '</td>'; }					
if ($_SESSION['login_role'] == 1) { echo '<td>' . $row['name'] . '</td>'; }					
echo	'<td><span class="badge label-table badge-success partner">' . $row['istochnik'] . '</span></td>' .
        '<td><i id="modclick" class="mdi mdi-border-color" style="cursor: pointer;"  data-id="' . $row['id'] . '" data-toggle="modal" data-target="#signup-modal"></i></td>' .
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

<? if ($_SESSION['login_role'] == 6) { $i_readonly = 'type="hidden"'; } else {$i_readonly = 'type="text"';}		
   if ($_SESSION['login_role'] == 6) { $i_readonlys = 'hidden'; } else {$i_readonlys = '';}		
   if ($_SESSION['login_role'] == 6) { $i_col12 = 'col-md-12'; } else {$i_col12 = 'col-md-6';}	?>	

<!-- Signup modal content -->
<div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-body">
				<h2 class="text-center m-b-30">Редактирование записи
				</h2>
				<form class="form-horizontal" action="update-h-unp.php" method="POST">
					<input type="hidden" id="idval" name="idval">
					<div class="form-row">
                        <div class="form-group col-md-6">
                        	<label for="fio">ФИО</label>
                            <input <? echo $i_readonly; ?> class="form-control" id="fio" name="fio" placeholder="ФИО" >
						</div>
                        <div class="form-group col-md-6">
                        	<label for="phone_number">Телефон</label>
                            <input <? echo $i_readonly; ?> class="form-control" id="phone_number" name="phone_number" placeholder="Телефон">
						</div>						
					</div>				   
					<div class="form-row">
                        <div class="form-group col-md-6">
                        	<label for="city">Город</label>
                            <input <? echo $i_readonly; ?> class="form-control" id="city" name="city" placeholder="Город">
						</div>
                        <div class="form-group <? echo $i_col12; ?>">
                        	<label for="status">Статус</label>
							<select id="status" name="status" class="form-control">
								<option selected="true" disabled="disabled">Статус</option>
									<?php
										$sql = $db_connect->query('SELECT * FROM `'. BEZ_DBPREFIX .'status` 
										                            WHERE status_id IN (1, 2, 3, 4, 5, 6, 7, 8, 9)');
										while ($line = $sql->fetchAssoc()) {
											echo '<option value="' . $line['status_id'] . '">' . $line['status_name'] . '</option>';
										}
									?>
                            </select>
						</div>						
					</div>	
					<div class="form-row">	
						<div class="form-group col-md-6">	
							<label for="user_name">Оператор</label>					
							<select <? echo $i_readonlys; ?> id="user_name" name="user_name" class="form-control">
								<option selected="true" disabled="disabled">Оператор</option>
									<?php
										$sql2 = $db_connect->query('SELECT * FROM `'. BEZ_DBPREFIX .'reg` WHERE role = 2');
										while ($line2 = $sql2->fetchAssoc()) {
											echo '<option value="' . $line2['id'] . '">' . $line2['id'] . '</option>';
										}
									?>
							</select>
						</div>	
                        <div class="form-group col-md-6">
                        	<label for="date_time_status_change">Дата и время</label>
							<input class="form-control" id="date_time_status_change" type="datetime-local" name="date_time_status_change" />
						</div>										
					</div>
					<div class="form-row">			
                        <div class="form-group col-md-12">
                        	<label for="address">Адрес</label>
							<select <? echo $i_readonlys; ?> id="address" name="address" class="form-control">
								<option selected="true" disabled="disabled">Адрес</option>
									<?php
										$sql3 = $db_connect->query('SELECT * FROM `st_addres_s`');
										while ($line3 = $sql3->fetchAssoc()) {
											echo '<option value="' . $line3['id'] . '">' . $line3['name_addres'] . '</option>';
										}
									?>
                            </select>	
						</div>			
					</div>						
					<div class="form-row">
                        <div class="form-group col-12">
                        	<label for="vopros">Комментарий</label>	
							<textarea <? echo $i_readonlys; ?> class="form-control" rows="5" id="vopros" name="vopros" placeholder="Комментарий кольщика по вопросу клиента."></textarea>
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