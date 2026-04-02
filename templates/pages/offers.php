<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; 
if(!$_SESSION['login_role']) {
	header('Location: /'); 
}
$err = isset($_GET['err'])  ? 'Неверный логин или пароль' : false;
$url = $_SERVER['REQUEST_URI']

?>
        <!-- Tooltipster css -->
        <link rel="stylesheet" href="/plugins/tooltipster/tooltipster.bundle.min.css">

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
					
							
<div class="checkbox checkbox-success"> 
	<input id="checkbox1" type="checkbox"><label for="checkbox1" id="tooltip-html"> БФЛ </label>
</div>
<div class="checkbox checkbox-success"> 
	<input id="checkbox2" type="checkbox"><label for="checkbox2" id="tooltip-html1"> ИПОТЕКА </label>
</div>	
<div class="checkbox checkbox-success"> 
	<input id="checkbox3" type="checkbox"><label for="checkbox3" id="tooltip-html2"> Юридические лиды </label>
</div>	
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
				<form class="form-horizontal" action="update-partner.php" method="POST">
					<input type="hidden" id="idval2" name="idval2">
					<div class="form-row">
                        <div class="form-group col-md-12">
							<label class="col-form-label">Название</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Пользователь">
						</div>
					</div>		
					<div class="form-row">
                        <div class="form-group col-md-12">
							<label class="col-form-label">Почта</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Почта">
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
				<h2 class="text-center m-b-30">Добавление партнера</h2>
				<form class="form-horizontal" action="create-partner.php" method="POST">
					<input type="hidden" id="idval" name="idval">
					<div class="form-row">
                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" id="name2" name="name2" placeholder="Партнер">
						</div>		
                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" id="email2" name="email2" placeholder="Почта">
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

        <!-- Tooltipster js -->
        <script src="/plugins/tooltipster/tooltipster.bundle.min.js"></script>
        <script src="/assets/pages/jquery.tooltipster.js"></script>


        <script>		
		$(document).ready(function(){
			$('a#modclick5').click(function(){				
				var sqlquery = $(this).attr('data-id1');
				var pId = sqlquery;
				$.ajax({
                    method: "POST",
					dataType: 'json',
                    url: "edit-partner.php",
                    data: {unp_id: pId, i_value: 'v_data'},
                    success: function(data) {
						$('#idval2').val(data.id);										
						$('#name').val(data.name);										
						$('#email').val(data.email);										
                    }
                });				
			});
			$('a#modclickdel').click(function(){		
				var sqlquery2 = $(this).attr('data-iddel');
				var pId2 = sqlquery2;
				$.ajax({
                    method: "POST",
                    url: "del-partner.php",
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