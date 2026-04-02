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

$res = $db_connect->query('SELECT bl.id as id,
								  u.user as user_login, 
								  u.name as user_name, 
								  bl.amount as amount
							 FROM balance bl
							 LEFT
							 JOIN bez_reg u
							   ON u.id = bl.user_id
');	




echo '<table class="table table-hover m-0 table-actions-bar dt-responsive nowrap" cellspacing="0" width="100%" id="datatable1">' .
		'<thead>' .
			'<tr>' .
				'<th>ИД</th>' .
				'<th>Логин</th>' .
				'<th>Имя</th>' .
				'<th>Сумма</th>'; 
echo		
			'</tr>' .
		'</thead>' .
	 '<tbody>';
 
 
while( $row = $res->fetchAssoc() ){	
echo '<tr>' .
		'<td>' . $row['id'] . '</td>' .
		'<td>' . $row['user_login'] . '</td>' .
		'<td>' . $row['user_name'] . '</td>' .
		'<td>' . $row['amount'] . '</td>';				
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

 <!-- Signup modal32 content -->
<div id="signup-modal32" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h2 class="text-center m-b-30">Добавление баланса</h2>
				<form class="form-horizontal" action="create-cash.php" method="POST">
					<input type="hidden" id="idval" name="idval" value="<?=$_SESSION['login']?>">
					<div class="form-row">
						<div class="form-group col-md-12">									
							<select id="part1" name="part1" class="form-control select2">
								<option selected="true" disabled="disabled">Рекламодатель</option>
								<?php
									$sql3 = $db_connect->query('SELECT * FROM `bez_reg` WHERE role = "6"');
									while ($line3 = $sql3->fetchAssoc()) {
										echo '<option value="' . $line3['id'] . '">' . $line3['user'] . ' ( '. $line3['name'] .' )</option>';
									}
								?>
							</select>
						</div>						
                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" id="amount" name="amount" placeholder="Сумма">
						</div>							
					</div>	

					<div class="form-group account-btn text-center m-t-10">
						<div class="col-12">
							<button id="savebut" class="btn w-lg btn-rounded btn-primary waves-effect waves-light" type="submit">Добавить</button>
						</div>
					</div>
		
				</form>
			</div>
		</div><!-- /.modal32-content -->
	</div><!-- /.modal32-dialog -->
</div><!-- /.modal32 -->

		


		
<?require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'; ?>

        <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="/plugins/datatables/dataTables.responsive.min.js"></script>

        <script>		
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
			

			
        </script>