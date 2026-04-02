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
<?
//echo '<pre>'; print_r($_SESSION); echo '</pre>';

if ($_SESSION['login_role'] == 1) {
	
	//echo '<pre>'; print_r($_SESSION); echo '</pre>';
	
}		

$res0 = $db_connect->query('SELECT 
								  `statistic`.`leads`,
								  `statistic`.`coming`,
								   100 * `statistic`.`coming` / `statistic`.`leads` as `KPD`
	
						     FROM `statistic` 		
						     LEFT
						     JOIN `'. BEZ_DBPREFIX .'reg`
						       ON ('. $_SESSION['login_role'] . '= 1
							       OR `'. BEZ_DBPREFIX .'reg`.`id` = `statistic`.`user_id` 
								   )
						  ');	
$stat = $res0->fetchAssoc();

			 
		 
?>	
		<!-- Start Page content -->
		<div class="content">
			<div class="container-fluid">

				<div class="row text-center">
					<div class="col-sm-6 col-lg-6 col-xl-4">
						<div class="card-box widget-flat border-custom bg-custom text-white">
							<i class="fi-tag"></i>
							<h3 class="m-b-10"><? echo $stat['leads']; ?></h3>
							<p class="text-uppercase m-b-5 font-13 font-600">Все заявки</p>
						</div>
					</div>
					<div class="col-sm-6 col-lg-6 col-xl-4">
						<div class="card-box bg-primary widget-flat border-primary text-white">
							<i class="fi-archive"></i>
							<h3 class="m-b-10"><? echo $stat['coming']; ?></h3>
							<p class="text-uppercase m-b-5 font-13 font-600">Все приходы</p>
						</div>
					</div>
					<div class="col-sm-6 col-lg-6 col-xl-4">
						<div class="card-box widget-flat border-success bg-success text-white">
							<i class="fi-help"></i>
							<h3 class="m-b-10"><? echo $stat['KPD']; ?> %</h3>
							<p class="text-uppercase m-b-5 font-13 font-600">Все КПД</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-12">
						<div class="card-box">					
							
<?
//echo '<pre>'; print_r($_SESSION); echo '</pre>';

if ($_SESSION['login_role'] == 1) {
	
	//echo '<pre>'; print_r($_SESSION); echo '</pre>';
	
}

$res1 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_start"');	    						  
$oper_date_s = $res1->fetchAssoc();	

$res2 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_end"');	    
$oper_date_e = $res2->fetchAssoc();		

$res3 = $db_connect->query('SELECT * FROM `bez_reg` WHERE login = "'.$_SESSION['login'].'"');	    
$login_id = $res3->fetchAssoc();		

$res = $db_connect->query('SELECT `'. BEZ_DBPREFIX .'reg`.`name`,
								  `statistic`.`leads`,
								  `statistic`.`coming`,
								   100 * `statistic`.`coming` / `statistic`.`leads` as `KPD`
	
						     FROM `statistic` 		
						     LEFT
						     JOIN `'. BEZ_DBPREFIX .'reg`
						       ON `'. BEZ_DBPREFIX .'reg`.`id` = `statistic`.`user_id`									   
						  ');	


echo '<table class="table table-hover m-0 table-actions-bar dt-responsive nowrap" cellspacing="0" width="100%" id="datatable1">' .
		'<thead>' .
			'<tr>' .
				'<th>ФИО</th>' .
				'<th>Заявки</th>' .
				'<th>Приходы</th>' .
				'<th>КПД</th>' .	
			'</tr>' .
		'</thead>' .
	 '<tbody>';
 
 
while( $row = $res->fetchAssoc() ){
echo '<tr>' .
		'<td>' . $row['name'] . '</td>' .
		'<td>' . $row['leads'] . '</td>' .
		'<td>' . $row['coming'] . '</td>' .
		'<td>' . $row['KPD'] . ' %</td>' .
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
		$(document).ready(function(){
			$('#singleCheckbox1').click(function(){	
			   if ($("input:checkbox").prop("checked")==false) {
				   uncheck($('#singleCheckbox2'));
				   
			   } else {
				   check($('#singleCheckbox2'));
			   }
			});		
		});		


		window.onload = function() {

		var checkbox;

		to_send.onclick = function() {

				checkbox = document.getElementsByName("list");

				var str = "";

				for(var i=0; i<checkbox.length; i++){

				if(checkbox[i].checked) {str+="'"+checkbox[i].value+"',";}
				var usernames = $('#user_names').val();
				}

				$.ajax({
                    method: "POST",
					//dataType: 'json',
                    url: "update-u.php",
                    data: {unp_id: str, i_value: usernames},
                    success: function(data) {
						//alert(data);
						location.reload();
                    }
                });
				
				//alert($('#user_names').val());

			}

		};


		$(document).ready(function(){
			$('i#modclick').click(function(){		
				var sqlquery = $(this).attr('data-id');
				var pId = sqlquery;
				$.ajax({
                    method: "POST",
					dataType: 'json',
                    url: "edit-h.php",
                    data: {unp_id: pId, i_value: 'v_data'},
                    success: function(data) {
						$('#fio').val(data.fio);
						$('#phone_number').val(data.phone_number);
						$('#vopros').val(data.vopros);
						$('#address').val(data.address);
						$('#city').val(data.city);
						$('#status').val(data.status);
						$('#date_create').val(data.date_create);						
						$('#timez').val(data.timez);						
						$('#idval').val(pId);						
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