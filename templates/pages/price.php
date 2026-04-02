<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; 
if(!$_SESSION['login_role']) {
	header('Location: /'); 
}
$err = isset($_GET['err'])  ? 'Неверный логин или пароль' : false;
$url = $_SERVER['REQUEST_URI']

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
					
							
<?
//echo '<pre>'; print_r($_SESSION); echo '</pre>';

if ($_SESSION['login_role'] == 1) {
	
	//echo '<pre>'; print_r($_SESSION); echo '</pre>';
	
}	


$res3 = $db_connect->query('SELECT * FROM `bez_reg` WHERE login = "'.$_SESSION['login'].'"');	    
$login_id = $res3->fetchAssoc();

$res = $db_connect->query('SELECT p.`id`, 
								  p.`city_id`, 
						          c.`name_city`, 
								  p.`amount`,
								  par.`partner_name` as user
						     FROM `price` as p 
							 LEFT
							 JOIN `bez_reg` as reg
							   ON p.`user_id` = reg.`id`
							 LEFT
							 JOIN `st_partner_s` as par
							   ON par.`id` = p.`user_id`							   
							 LEFT
						     JOIN `st_city_s` as c 
							   ON c.`id` = p.`city_id`
						    WHERE p.`user_id` = "' . $login_id['id'] . '" 
							   OR (1=1 AND 1 = "' . $login_id['role'] . '")
							   OR par.`id` = p.`user_id`
							');	


echo '<table class="table table-hover m-0 table-actions-bar dt-responsive nowrap" cellspacing="0" width="100%" id="datatable1">' .
		'<thead>' .
			'<tr>' .
				'<th>Регион</th>' .
				'<th>Стоимость</th>'; 
if ($_SESSION['login_role'] == 1) { echo  '<th>Партнер</th>'; }				
if ($_SESSION['login_role'] == 1) { echo  '<th></th>'; }				
echo			'</tr>';
		'</thead>' .
	 '<tbody>';
 
 
while( $row = $res->fetchAssoc() ){	
echo '<tr>' .
		'<td>' . $row['name_city'] . '</td>' .
		'<td>' . $row['amount'] . '</td>';	
if ($_SESSION['login_role'] == 1) { echo  '<td>' . $row['user'] . '</td>'; }	
if ($_SESSION['login_role'] == 1) {		
echo	'<td><div class="btn-group dropdown">
              <a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
              <div class="dropdown-menu dropdown-menu-right">
               <a id="modclick5" class="dropdown-item" href="#" data-id1="' . $row['id'] . '" data-toggle="modal" data-target="#signup-modal5"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Изменить</a>
               <a id="modclickdel" class="dropdown-item" href="#" data-iddel="' . $row['id'] . '"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Удалить</a>
              </div>
             </div>
		</td>';
}
		
echo	 '</tr>';
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
				<form class="form-horizontal" action="update-price.php" method="POST">
					<input type="hidden" id="idval2" name="idval2">
					<div class="form-row">
						<div class="form-group col-md-12">									
							<select id="part1" name="part1" class="form-control select2">
								<option selected="true" disabled="disabled">Партнер</option>
								<?php
									$sql3 = $db_connect->query('SELECT par.id as id, par.partner_name as partner_name FROM `st_partner_s` as par WHERE par.user_id = 0 UNION ALL 
																SELECT par.id as id, par.partner_name as partner_name FROM `st_partner_s` as par JOIN `balance` as bal ON par.user_id = bal.user_id AND bal.amount > 0');
									while ($line3 = $sql3->fetchAssoc()) {
										echo '<option value="' . $line3['id'] . '">' . $line3['partner_name'] . '</option>';
									}
								?>
							</select>
 
							
						</div>	
						<div class="form-group col-md-12">							
						<select id="city1" name="city1" class="form-control" >
							<option selected="true" disabled="disabled">Город</option>
								<?php
									$sql3 = $db_connect->query('SELECT * FROM `st_city_s`');
									while ($line3 = $sql3->fetchAssoc()) {
										echo '<option value="' . $line3['id'] . '">' . $line3['name_city'] . '</option>';
									}
								?>
						</select>
						</div>	
						<div class="form-group col-md-12">	
							<input type="text" class="form-control" id="price2" name="price2" placeholder="Стоимость">
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

 <!-- Signup modal30 content -->
<div id="signup-modal30" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h2 class="text-center m-b-30">Добавление к тарифу</h2>
				<form class="form-horizontal" action="create-price.php" method="POST">
					<input type="hidden" id="idval3" name="idval3">
					<div class="form-row">
						<div class="form-group col-md-12">									
							<select id="part1" name="part1" class="form-control select2">
								<option selected="true" disabled="disabled">Партнер</option>
								<?php
									$sql3 = $db_connect->query('SELECT par.id as id, par.partner_name as partner_name FROM `st_partner_s` as par WHERE par.user_id = 0 UNION ALL 
																SELECT par.id as id, par.partner_name as partner_name FROM `st_partner_s` as par JOIN `balance` as bal ON par.user_id = bal.user_id AND bal.amount > 0');
									while ($line3 = $sql3->fetchAssoc()) {
										echo '<option value="' . $line3['id'] . '">' . $line3['partner_name'] . '</option>';
									}
								?>
							</select>
 
							
						</div>	
						<div class="form-group col-md-12">							
						<select id="city1" name="city1" class="form-control" >
							<option selected="true" disabled="disabled">Город</option>
								<?php
									$sql3 = $db_connect->query('SELECT * FROM `st_city_s`');
									while ($line3 = $sql3->fetchAssoc()) {
										echo '<option value="' . $line3['id'] . '">' . $line3['name_city'] . '</option>';
									}
								?>
						</select>
						</div>	
						<div class="form-group col-md-12">	
							<input type="text" class="form-control" id="price2" name="price2" placeholder="Стоимость">
						</div>

										
					</div>	

					<div class="form-group account-btn text-center m-t-10">
						<div class="col-12">
							<button id="savebut" class="btn w-lg btn-rounded btn-primary waves-effect waves-light" type="submit">Добавить</button>
						</div>
					</div>
		
				</form>
			</div>
		</div><!-- /.modal30-content -->
	</div><!-- /.modal30-dialog -->
</div><!-- /.modal30 -->

		


		
<?require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'; ?>

        <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="/plugins/datatables/dataTables.responsive.min.js"></script>
	
		
		
		
		
        <script>		
		$(document).ready(function(){
			$('a#modclick5').click(function(){				
				var sqlquery = $(this).attr('data-id1');
				var pId = sqlquery;
				$.ajax({
                    method: "POST",
					dataType: 'json',
                    url: "edit-price.php",
                    data: {unp_id: pId, i_value: 'v_data'},
                    success: function(data) {
						$('#idval2').val(data.id);										
						$('#part1').val(data.user_id);										
						$('#city1').val(data.city_id);										
						$('#price2').val(data.amount);										
                    }
                });				
			});
			$('a#modclickdel').click(function(){		
				var sqlquery2 = $(this).attr('data-iddel');
				var pId2 = sqlquery2;
				$.ajax({
                    method: "POST",
                    url: "del-price.php",
                    data: {unp_id2: pId2, i_value: 'v_data'},
                    success: function(data) {
						alert('Запись успешно удалена.');
						location.reload();
                    }
                });				
			});		
			
			
            $(document).ready(function () {
                $('#datatable1').dataTable({	
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