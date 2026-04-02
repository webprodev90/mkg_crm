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
if ($_SESSION['login_role'] == 1) {
	
	//echo '<pre>'; print_r($_SESSION); echo '</pre>';
	
}

$res1 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_start" AND login_id = "' . $_SESSION['login_id'] . '"');    
$oper_date_s = $res1->fetchAssoc(); 

$res2 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_end" AND login_id = "' . $_SESSION['login_id'] . '"');       
$oper_date_e = $res2->fetchAssoc(); 

$res = $db_connect->query('

SELECT IFNULL(s.id,999999999) as id, 
       u.id as user_id, 
	   u.name as user_name, 
	   IFNULL(s.work_hours,8) as work_hours
  FROM bez_reg u
  LEFT
  JOIN st_report_user s
    ON s.user_id = u.id
   AND date(s.row_change_time) between date("'.$oper_date_s['date_value'].'") and date("'.$oper_date_e['date_value'].'")
 WHERE u.role = 5
   AND ( ('.$_SESSION['login_role'].' <> 1 and u.id_otdel = '.$_SESSION['id_otdel'].') 
			or
		 ('.$_SESSION['login_role'].' = 1)	
	   )
 ORDER BY id DESC
 LIMIT 500

');	


echo '<table class="table table-hover m-0 table-actions-bar dt-responsive nowrap" cellspacing="0" width="100%" id="datatable1">' .
		'<thead>' .
			'<tr>' .
				'<th>Оператор</th>' . 
				'<th>Часов отработано</th>'; 
echo			'<th></th>' .
			'</tr>' .
		'</thead>' .
	 '<tbody>';
 
 
while( $row = $res->fetchAssoc() ){	
echo '<tr>' .
		'<td>' . $row['user_name'] . '</td>' .				
		'<td>' . $row['work_hours'] . '</td>';				
echo	'<td><div class="btn-group dropdown">
              <a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
              <div class="dropdown-menu dropdown-menu-right">
               <a id="modclick5" class="dropdown-item" href="#" data-id1="' . $row['id'] . '" data-user_id="' . $row['user_id'] . '" data-toggle="modal" data-target="#signup-modal5"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Изменить</a>
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
				<form class="form-horizontal" action="update-report_user.php" method="POST">
					<input type="hidden" id="user_id" name="user_id">
					<input type="hidden" id="idval2" name="idval2">
					<input type="hidden" id="date_start" name="date_start">
					<input type="hidden" id="date_end" name="date_end">
					<div class="form-row">
                        <div class="form-group col-md-12">
							<label class="col-form-label">Часов отработано</label>
                            <input type="text" class="form-control" id="work_hours" name="work_hours" placeholder="Часов отработано">
						</div>
					</div>			
					<div class="form-group account-btn text-center m-t-10">
						<div class="col-12">
							<button id="savebut" class="btn w-lg btn-rounded btn-primary waves-effect waves-light <?= $oper_date_s['date_value'] == $oper_date_e['date_value'] ? '' : 'no-edit' ?>" type="submit">Сохранить</button>
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
		$(document).ready(function(){
			$('a#modclick5').click(function(){				
				var sqlquery = $(this).attr('data-id1');
				var sqlquery2 = $(this).attr('data-user_id');
				var pId = sqlquery;
				var puser_id = sqlquery2;
				var dates = $('#reportrange').val().split(' - ');
                var date_start = dates[0].split('/').reverse().join('-');
                var date_end = dates[1].split('/').reverse().join('-');
				$.ajax({
                    method: "POST",
					dataType: 'json',
                    url: "edit-report_user.php",
                    data: {unp_id: pId, user_id: puser_id, i_value: 'v_data', date_start: date_start, date_end: date_end},
                    success: function(data) {
						$('#idval2').val(data.id);	
						$('#user_id').val(data.user_id);
						$('#work_hours').val(data.work_hours);		
						$('#date_start').val(data.date_start);
						$('#date_end').val(data.date_end);																
                    }
                });				
			});

			$('#savebut.no-edit').click(function(e){
				e.preventDefault();
				alert("В диапазоне дат выставлен не один день, а больше!");
			});	
			
            $(document).ready(function () {
                $('#datatable1').dataTable({
					searching: false,	
					paging: false,		
					info: false,					
					"bLengthChange": false,
					"pageLength": 500,					
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