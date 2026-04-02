<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; 
if(!$_SESSION['login_role']) {
	header('Location: /'); 
}
$err = isset($_GET['err'])  ? 'Неверный логин или пароль' : false;
$url = $_SERVER['REQUEST_URI'];
/*if ($_GET['p']) {
	$param1 = $_GET['p'];
} else {
	$param1 = '10';
}*/
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
if ($_SESSION['login_role'] == 1 and $_SESSION['debug'] == 'Y') {
	echo '<pre>'; print_r($_SESSION); echo '</pre>';
}

$res1 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_start" AND login_id = "' . $_SESSION['login_id'] . '"');	    						  
$oper_date_s = $res1->fetchAssoc();	

$res2 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_end" AND login_id = "' . $_SESSION['login_id'] . '"');	    
$oper_date_e = $res2->fetchAssoc();		

$res3 = $db_connect->query('SELECT * FROM `bez_reg` WHERE login = "'.$_SESSION['login'].'"');	    
$login_id = $res3->fetchAssoc();	

$hide_partner_411 = '';

if($_SESSION['login_id'] != 132 && $_SESSION['login_id'] != 133 && $_SESSION['login_id'] != 252 && $_SESSION['login_id'] != 119 && $_SESSION['login_id'] != 306) {
    $hide_partner_411 = 'AND st_partner_s.id <> 411';
}	

$res = $db_connect->query("SELECT id, partner_name, SUM(subCol) as col
							FROM 
							(SELECT st_partner_s.id, st_partner_s.partner_name, count(distinct bez_unprocessed_base.id) as subCol
							FROM bez_sale_request bsr1
							JOIN bez_unprocessed_base
							ON bez_unprocessed_base.id = bsr1.request_id		 
							JOIN st_partner_s
							ON st_partner_s.id = bsr1.partner_id							    							   
							WHERE bsr1.date_time BETWEEN '{$oper_date_s['date_value']} 00:00:00' AND '{$oper_date_e['date_value']} 23:59:59'         
							AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr1.partner_id <> 65 and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time))) {$hide_partner_411}
							GROUP BY st_partner_s.partner_name
							UNION ALL
							SELECT st_partner_s.id, st_partner_s.partner_name, count(distinct bez_unprocessed_base.id) as subCol
							FROM bez_unprocessed_base	 
							JOIN st_partner_s
							ON st_partner_s.id = bez_unprocessed_base.partner							    							   
							WHERE bez_unprocessed_base.is_ship = '1' AND DATE_FORMAT(bez_unprocessed_base.date_create, '%Y-%m-%d') between DATE_FORMAT('{$oper_date_s['date_value']}', '%Y-%m-%d') and DATE_FORMAT('{$oper_date_e['date_value']}', '%Y-%m-%d') AND bez_unprocessed_base.source = 'telegram' AND bez_unprocessed_base.user_id <> '' {$hide_partner_411}
							GROUP BY st_partner_s.partner_name) AS Table1
							GROUP BY id, partner_name;
							");

$partners = [];
while($row = $res->fetchAssoc()) {
   $partners[] = $row;
} 

$total_col = 0;
foreach($partners as $row) {
	if($row['id'] != 65) {
		$total_col += $row['col'];
	}
}


echo '<div><b>Общее количество заявок:</b> ' . $total_col . '</div><br>';

echo '<table class="table table-hover m-0 table-actions-bar  nowrap cellspacing="0" width="100%" id="datatable1"><thead><tr>';	
		   echo '<th>id</th>' .
				'<th>Имя</th>' .
				'<th>Кол-во заявок</th>';
echo			'<th></th>' .
			'</tr>' .
		'</thead>' .
	 '<tbody>';
 
foreach ($partners as $row){
echo '<tr>';
   echo '<td>' . $row['id'] . '</td>' .
        '<td>' . $row['partner_name'] . '</td>' .
		'<td>' . $row['col'] . '</td>' ;					
echo	'<td><i id="modclick2" class="mdi mdi-border-color" style="cursor: pointer;"  data-oper-s="' . $oper_date_s['date_value'] . '" data-oper-e="' . $oper_date_e['date_value'] . '" data-id="' . $row['id'] . '" data-toggle="modal" data-target="#signup-modal-z"> Подробнее  </i>' .
    	'|<i id="modclick3" class="mdi mdi-border-color1" style="cursor: pointer;"  data-oper-s="' . $oper_date_s['date_value'] . '" data-oper-e="' . $oper_date_e['date_value'] . '" data-id="' . $row['id'] . '"> Загрузить CSV </i></td>' .
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
<div id="signup-modal-z" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-body">
				<h2 class="text-center m-b-30">Заявки</h2>
				
				
				<div id="tablepodr"></div>	

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

			if (checkbox[i].checked) { str += "'" + checkbox[i].value + "',"; }
			var usernames = $('#user_names').val();
		}

		$.ajax({
			method: "POST",
			//dataType: 'json',
			url: "updatebas-u.php",
			data: { unp_id: str, i_value: usernames },
			success: function (data) {
				//alert(data);
				location.reload();
			}
		});

		//alert($('#user_names').val());

	}

};


$(document).ready(function () {
	$('i#modclick').click(function () {
		var sqlquery = $(this).attr('data-id');
		var pId = sqlquery;
		$.ajax({
			method: "POST",
			dataType: 'json',
			url: "editbas-h.php",
			data: { unp_id: pId, i_value: 'v_data' },
			success: function (data) {
				$('#fio').val(data.fio);
				$('#phone_number').val(data.phone_number);
				$('#vopros').val(data.vopros);
				$('#partner').val(data.partner);
				$('#city').val(data.city);
				$('#status').val(data.status);
				$('#date_create').val(data.date_create);
				$('#timez').val(data.timez);
				$('#idval').val(pId);
				$('#user_name').val(data.user_name);
			}
		});


	});
});

$(document).ready(function () {
	$('i#modclick2').click(function () {

		var sqlquery = $(this).attr('data-id');
		var sqlquery2 = $(this).attr('data-oper-s');
		var sqlquery3 = $(this).attr('data-oper-e');
		var pId = sqlquery;
		var pId2 = sqlquery2;
		var pId3 = sqlquery3;
		$.ajax({
			method: "POST",
			dataType: 'json',
			url: "partner-h.php",
			data: { unp_id: pId, unp_id2: pId2, unp_id3: pId3, i_value: 'v_data' },
			success: function (data) {
				//alert(data.html);
				$('#tablepodr').html(data.html);
			}
		});
	});
});

$(document).ready(function () {
	$('i#modclick3').click(function () {

		var sqlquery = $(this).attr('data-id');
		var sqlquery2 = $(this).attr('data-oper-s');
		var sqlquery3 = $(this).attr('data-oper-e');
		var pId = sqlquery;
		var pId2 = sqlquery2;
		var pId3 = sqlquery3;
		$.ajax({
			method: "POST",
			url: "partner-csv.php",
			data: { unp_id: pId, unp_id2: pId2, unp_id3: pId3, i_value: 'v_data' },
			success: function (data) {
				document.location.href = data;
			}
		});
	});
});


$(document).ready(function () {
	$('#datatable1').dataTable({
		"scrollX": true,
		"bAutoWidth": false,
		"iDisplayLength": 50,
		"language": {
			"sProcessing": "Procesando...",
			"sLengthMenu": "Показывать _MENU_ записей",
			"sZeroRecords": "No se encontraron resultados",
			"sEmptyTable": "Нет данных",
			"sInfo": "Отображение записей от _START_ до _END_ из общего количества _TOTAL_ записей",
			"sInfoEmpty": "Отображение записей от 0 до 0 из общего количества 0 записей",
			"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix": "",
			"sSearch": "Поиск:",
			"sUrl": "",
			"sInfoThousands": ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst": "Предыдущий",
				"sLast": "Следующий",
				"sNext": "Далее",
				"sPrevious": "Назад"
			},
			"oAria": {
				"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		}

	});
});
</script>
