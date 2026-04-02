<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; 
if(!$_SESSION['login_role']) {
	header('Location: /'); 
}
$err = isset($_GET['err'])  ? 'Неверный логин или пароль' : false;
$url = $_SERVER['REQUEST_URI']

?>
<style>
th.sorting {
    width: 5% !important;
}
th.sorting.sorting_asc {
    width: 12% !important;
}

.card-box {
    overflow: auto;
    max-height: 700px;
    padding-top: 0;
}

.table thead th {
	position: sticky;
    top: -1px;
    background-color: #ffffff;
}

<?php if($_SESSION['login_role'] == 4 OR $_SESSION['login_role'] == 5): ?>

	.table {
		table-layout: fixed;
	}

	.table th:nth-child(1) {
		width: 5%;
	}

	.table th:nth-child(2) {
		width: 20%;
	}

<?php endif; ?>

</style>

<?
$hours_filtr = '';
for($i = 8; $i <= 20; $i++) {
	$hours_filtr .= "<option value=\"{$i}\">{$i}:00</option>";
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
						<div class="pt-3 pl-3 pb-3 bg-white">
                            <div class="row align-items-center ml-1" style="gap: 10px;">
                            	<div>С</div>
                            	<div class="form-inline">
                                    <select class="form-control select-start-time" name="start-time">
                                        <option value="" selected="">Выбор времени</option>
                                        	<?= $hours_filtr ?>
                                    </select>
                                </div>
                                <div>до</div>
                                <div class="form-inline">
                                    <select class="form-control select-end-time" name="end-time">
                                        <option value="" selected="">Выбор времени</option>
                                        	<?= $hours_filtr ?>
                                    </select>
                                </div>
                                <button class="btn btn-primary btn-time-filter">Показать</button> 
                            </div>
                        </div>						
						<div class="card-box">	
						<table class="table table-hover m-0 table-actions-bar dt-responsive nowrap" cellspacing="0" width="100%" id="datatable1">
							<thead>
								<tr>
									<th>Отдел</th>
									<th>ФИО оператора</th>
									<th>Контактов</th>
									<th>АО</th>
									<th>НДЗ</th>
									<th>Отказы</th>
									<th>Дозвонов</th>
									<th>Созвоны</th>
									<th>Лиды</th>
									<?php if($_SESSION['login_role'] != 5): ?>
										<?php if($_SESSION['login_role'] != 4): ?>
											<th>Чистый лид</th>
											<th>Холд</th>
											<th>Грязный Холд</th>				
											<th>Отраб. часы</th>
										<?php endif; ?>
										<th>Дозвонов в час</th>	
										<?php if($_SESSION['login_role'] != 4): ?>
											<th>Лидов в час</th>
											<th>% НДЗ</th>
											<th>% АО</th>
										<?php endif; ?>
										<th>% дозвон/лид</th>
										<?php if($_SESSION['login_role'] != 4): ?>
											<th>% контакт/лид</th>	
											<th>% холд/лид</th>
											<th>% грязный холд/лид</th>			
											<th>Звонков в час</th>
											<th>Cр. время заполнения карточки лида (сек.)</th>
											<th>Cр. время диалога оператора (сек.)</th>
										<?php endif; ?>
									<?php endif; ?>
								</tr>
							</thead>
						 <tbody>

						 </tbody>
						</table>	
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
<script src="/scripts/atc-stat.js"></script>