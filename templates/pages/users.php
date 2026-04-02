<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; 
if(!$_SESSION['login_role']) {
	header('Location: /'); 
}
$err = isset($_GET['err'])  ? 'Неверный логин или пароль' : false;
$url = $_SERVER['REQUEST_URI'];

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
                            <? if ($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4) { ?>
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-6 text-sm-center form-inline">
                                        <div class="form-group mr-2">
                                            <select id="user_names" name="user_names" class="form-control">
                                                <option selected="">Вкл/выкл пользователя</option>
                                                <?php
													echo '<option value="2">Выключить</option>';
													echo '<option value="1">Включить</option>';
												?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <button id="to_send" class="btn btn-primary"></i>Обновить</button>
                                        </div>
                                        <? if ($_SESSION['login_role'] == 1) { ?>
	                                        <div class="form-group ml-2">  	
	                                        	<?php
													$res4 = $db_connect->query('SELECT * FROM options WHERE param_name = "EXCELON"');
													$opt = $res4->fetchAssoc();	
	                                        	?>
	                                            <select id="access_settings" name="access_settings" class="form-control">
	                                                <option value="1" <?= $opt['number_value'] == 1 ? ' selected' : ''; ?>>Вкладка доступна пользователям</option>
	                                                <option value="0" <?= $opt['number_value'] == 0 ? ' selected' : ''; ?>>Вкладка недоступна пользователям</option>
	                                            </select>
	                                        </div>
                                        <? } ?>
                                    </div>
                                    <div class="col-6 text-sm-center form-inline">
                                    </div>
                                </div>
                            </div>
                            <? } ?>					
							
<?
if ($_SESSION['login_role'] == 1) {
	
	//echo '<pre>'; print_r($_SESSION); echo '</pre>';
	
}

$res1 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_start"');	    						  
$oper_date_s = $res1->fetchAssoc();	

$res2 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_end"');	    
$oper_date_e = $res2->fetchAssoc();		

$res3 = $db_connect->query('SELECT * FROM `bez_reg` WHERE login = "'.$_SESSION['login'].'"');	    
$login_id = $res3->fetchAssoc();

$where_operators = '';
if ($_SESSION['login_role'] == 4) {
	$where_operators = 'AND role = 5 AND id_otdel = ' . $_SESSION['id_otdel'];
}

$res = $db_connect->query('SELECT `'. BEZ_DBPREFIX .'reg`.`id`,
								  `'. BEZ_DBPREFIX .'reg`.`login`,
								  `'. BEZ_DBPREFIX .'reg`.`pass`,
								  `'. BEZ_DBPREFIX .'reg`.`salt`,
								  `'. BEZ_DBPREFIX .'reg`.`active_hex`,
								  `'. BEZ_DBPREFIX .'reg`.`status`,
								  `'. BEZ_DBPREFIX .'reg`.`role`,
								  `'. BEZ_DBPREFIX .'reg`.`name`,
								  `'. BEZ_DBPREFIX .'reg`.`user`,
								  `'. BEZ_DBPREFIX .'reg`.`address_id`,
								  `'. BEZ_DBPREFIX .'reg`.`online`,
								  `'. BEZ_DBPREFIX .'reg`.`id_otdel`,
								  bez_role.name_role
	
						     FROM `'. BEZ_DBPREFIX .'reg` 
							 LEFT
							 JOIN bez_role
							   ON `'. BEZ_DBPREFIX .'reg`.`role` = bez_role.role_id
							WHERE login != "bratva1990@yandex.ru" ' . $where_operators . '
							ORDER BY `'. BEZ_DBPREFIX .'reg`.`id` DESC
						  ');	


echo '<table class="table table-hover m-0 table-actions-bar dt-responsive nowrap" cellspacing="0" width="100%" id="datatable1">';
	  echo '<thead>' .
			'<tr>' ;
if ($_SESSION['login_role'] == 1 OR $_SESSION['login_role'] == 4) { 
	       echo '<th><input type="checkbox" id="singleCheckbox1" value="option2" aria-label="Single checkbox Two"></th>'; }				
		echo	'<th>Логин</th>' .
				'<th>Пользователь</th>' .
				'<th>Почта</th>' .
				'<th>Роль</th>' .
				'<th>Отдел</th>' .
				'<th>Статус</th>'; 
echo			'<th></th>' .
			'</tr>' .
		'</thead>' .
	 '<tbody>';
 
 
while( $row = $res->fetchAssoc() ){
$time1 = $row['online'] + 50 ;
if ($time1 >= time()) { $flagtime = '<span class="badge label-table badge-success">Онлайн</span>'; } 
elseif ($row['status'] == 2) { $flagtime = '<span class="badge label-table badge-warning">Отключен</span>'; } 
else { $flagtime = '<span class="badge label-table badge-danger">Оффлайн</span>'; }	
echo '<tr>';
if ($_SESSION['login_role'] == 1  OR $_SESSION['login_role'] == 4) { 
   echo '<td><input type="checkbox" id="singleCheckbox2" name="list" value="' . $row['id'] . '" aria-label="Single checkbox Two"></td>';}
   echo '<td>' . $row['user'] . '</td>' .
		'<td>' . $row['name'] . '</td>' .
		'<td>' . $row['login'] . '</td>' .
		'<td>' . $row['name_role'] . '</td>' .
		'<td>' . $row['id_otdel'] . '</td>' .
		'<td>' . $flagtime . '</td>';				
echo	'<td><div class="btn-group dropdown">
              <a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
              <div class="dropdown-menu dropdown-menu-right">
               <a id="modclick5" class="dropdown-item" href="#" data-id1="' . $row['id'] . '" data-toggle="modal" data-target="#signup-modal5"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Изменить</a>
               <a id="modclickdel" class="dropdown-item" href="#" data-iddel="' . $row['id'] . '"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Удалить</a>
              </div>
             </div>
	 </tr>';
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
<div id="signup-modal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-body">
				<h2 class="text-center m-b-30">Редактирование записи
				</h2>
				<form class="form-horizontal" action="update-user.php" method="POST">
					<input type="hidden" id="idval2" name="idval2">
					<div class="form-row">
                        <div class="form-group col-md-6">
							<label class="col-form-label">Пользователь</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Пользователь">
						</div>
                        <div class="form-group col-md-6">
							<label class="col-form-label">Почта</label>
                            <input type="text" class="form-control" id="login" name="login" placeholder="Почта">
						</div>						
					</div>				   
					<div class="form-row">
                        <div class="form-group col-md-6">
						    <label class="col-form-label">Роль</label>
							<select id="role" name="role" class="form-control">
								<option selected="">Роль</option>
									<?php
										$where_role = '';
										if ($_SESSION['login_role'] == 4) {
											$where_role = 'WHERE role_id = 5';
										}
										$sql = $db_connect->query('SELECT * FROM bez_role ' . $where_role);
										while ($line = $sql->fetchAssoc()) {
											echo '<option value="' . $line['role_id'] . '">' . $line['name_role'] . '</option>';
										}
									?>
                            </select>
						</div>	
                        <div class="form-group col-md-6">
							<label class="col-form-label">Пароль</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
						</div>			
						<?/*?>
                        <div class="form-group col-md-6">
							<select id="address" name="address" class="form-control">
								<option selected="true" disabled="disabled">Адрес</option>
									<?php
										$sql3 = $db_connect->query('SELECT * FROM `st_addres_s`');
										while ($line3 = $sql3->fetchAssoc()) {
											echo '<option value="' . $line3['id'] . '">' . $line3['name_addres'] . '</option>';
										}
									?>
                            </select>	
						</div>		
						<?*/?>
						</div>	
						<div class="form-row">
							<div class="form-group col-md-6">
								<label class="col-form-label">ATC ID</label>
								<select id="id_atc" name="id_atc" class="form-control">
									    <option value="0">Неназначено</option>
										<?php
											$sql = $db_connect->query('SELECT number_value FROM (  
																		SELECT s.number_value, r.id_atc    
																		  FROM options s 
																		  LEFT
																		  JOIN bez_reg r 
																			on r.id_atc = s.number_value
																		 WHERE s.param_name = "ATCID") t
																		WHERE id_atc is null');
											while ($line = $sql->fetchAssoc()) {
												echo '<option value="' . $line['number_value'] . '">' . $line['number_value'] . '</option>';
											}
										?>
								</select>
							</div>		
							<div class="form-group col-md-6">
								<label class="col-form-label">Отдел</label>
								<select id="id_otdel" name="id_otdel" class="form-control">
									<option selected="true" disabled="disabled">Отдел</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
								</select>	
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

        <script>
function check() {
	$("input:checkbox").prop("checked", true);
};
function uncheck() {
	$("input:checkbox").prop("checked", false);
};
$(document).ready(function () {
	$('#singleCheckbox1').click(function () {
		if ($("input:checkbox").prop("checked") == false) {
			uncheck($('#singleCheckbox2'));

		} else {
			check($('#singleCheckbox2'));
		}
	});
});
		
		
		
window.onload = function () {

	var checkbox;

	to_send.onclick = function () {

		checkbox = document.getElementsByName("list");

		var str = "";

		for (var i = 0; i < checkbox.length; i++) {

			if (checkbox[i].checked) { str += '"' + checkbox[i].value + '",'; }
			var usernames = $('#user_names').val();
		}

		$.ajax({
			method: "POST",
			//dataType: 'json',
			url: "update-off-on-u.php",
			data: { unp_id: str, i_value: usernames },
			success: function (data) {
				//alert(data);
				location.reload();
			}
		});

		//alert($('#user_names').val());

	}

};		
		
		
		$(document).ready(function(){
			$('a#modclick5').click(function(){				
				var sqlquery = $(this).attr('data-id1');
				var pId = sqlquery;

				$.ajax({
                    method: "POST",
					dataType: 'json',
                    url: "edit-u.php",
                    data: {unp_id: pId, i_value: 'v_data'},
                    success: function(data) {
						$('#idval2').val(pId);							
						$('#name').val(data.name);
						$('#address').val(data.address);
						$('#id_otdel').val(data.id_otdel);
						$('#login').val(data.login);
						$('#role').val(data.role);
						$('#password').val(data.password);											
						$('#id_atc').prepend($('<option>', {
							value: data.id_atc,
							text: data.id_atc,
							disabled: true
						}));
						$('select#id_atc option[value="'+data.id_atc+'"]').prop('selected', true);						
                    }
                });				
			});
			$('a#modclickdel').click(function(){		
				var sqlquery2 = $(this).attr('data-iddel');
				var pId2 = sqlquery2;
				$.ajax({
                    method: "POST",
                    url: "del-u.php",
                    data: {unp_id2: pId2, i_value: 'v_data'},
                    success: function(data) {
						alert('Запись успешно удалена.');
						location.reload();
                    }
                });				
			});	

			$('#access_settings').change(function(){		
				var value_access = $(this).val();
				$.ajax({
                    method: "POST",
                    url: "update-access-excel.php",
                    data: {value_access: value_access},
                    success: function(data) {
						alert('Доступ обновлен');
                    }
                });			
			});		

			$('.create-user-btn').click(function(){	
				let name = $('#form-create-user').find('[name="name"]').val();			
				let user = $('#form-create-user').find('[name="user"]').val();
				let login = $('#form-create-user').find('[name="login"]').val();
				let role = $('#form-create-user').find('[name="role"]').val();
				let pass = $('#form-create-user').find('[name="pass"]').val();	
				let id_otdel = $('#form-create-user').find('[name="id_otdel"]').val();		
				
				$.ajax({
                    method: "POST",
					dataType: 'json',
                    url: "create-u.php",
                    data: {
                    	name,
                    	user,
                    	login,
                    	role,
                    	pass,
                    	id_otdel
                    },
                    success: function(data) {
                    	if(data.response === 'success') {
                    		location.reload();
                    	} else {
							alert(data.response);
                    	}				
                    }
                });	
               		
			});	
			
            $(document).ready(function () {
                $('#datatable1').dataTable({	
					"pageLength": 100,
					"language": {
						"sProcessing":    "Procesando...",
						"sLengthMenu":    "Показывать _MENU_ записей",
						"sZeroRecords":   "No se encontraron resultados",
						"sEmptyTable":    "Нет данных",
						"sInfo":          "Отображение записей от _START_ до _END_ из общего количества _TOTAL_ записей",
						"sInfoEmpty":     "Отображение записей от 0 до 0 из общего количества 0 записей",
						"sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
						"sInfoPostFix":   "",
						"sSearch":        "Поиск:",
						"sUrl":           "",
						"sInfoThousands":  ",",
						"sLoadingRecords": "Cargando...",
						"oPaginate": {
							"sFirst":    "Предыдущий",
							"sLast":     "Следующий",
							"sNext":     "Далее",
							"sPrevious": "Назад"
						},
						"oAria": {
							"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
							"sSortDescending": ": Activar para ordenar la columna de manera descendente"
						}	
					}
					
				});
            });
}); 			

			
        </script>