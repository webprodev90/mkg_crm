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
					
				<form class="form-horizontal" action="/templates/pages/del-record.php" method="POST">
					Удалить запись с 
					<input type="text" id="unp_id" name="unp_id">
					 по <input type="text" id="unp_id2" name="unp_id2">
					<div class="form-group account-btn text-center m-t-10">
						<div class="col-12">
							<button id="savebut" class="btn w-lg btn-rounded btn-primary waves-effect waves-light" type="submit">Сохранить</button>
						</div>
					</div>
		
				</form>
				
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
		/*$(document).ready(function(){
			$('a#modclickdel').click(function(){		
				var sqlquery = $(this).attr('data-iddel');
				var sqlquery2 = $(this).attr('data-iddel2');
				var pId = sqlquery;
				var pId2 = sqlquery2;
				$.ajax({
                    method: "POST",
                    url: "del-addres.php",
                    data: {unp_id: pId, unp_id2: pId2},
                    success: function(data) {
						alert('Записи успешно удалены.');
						location.reload();
                    }
                });				
			});		
			
}); 	*/		

			
        </script>