 <!-- Signup modal content -->
<div id="signup-modal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h2 class="text-center m-b-30">Добавление клиента</h2>
				<?/*print_r($_SESSION);*/?>
				<form class="form-horizontal" action="create-h.php" method="POST">
					<input type="hidden" id="idval" name="idval">
					<div class="form-row">
                        <div class="form-group col-md-12">
                            <input type="hidden" id="fio" name="otdel" value="<?=$_SESSION['id_otdel']?>">
                            <input type="hidden" id="user" name="user" value="<?=$_SESSION['login_id']?>">
                            <input type="hidden" id="urlc" name="urlc" value="<?=substr_count($_SERVER['REQUEST_URI'], 'unprocessed.php')?>">
                            <input type="text" class="form-control" id="fio" name="fio" placeholder="ФИО">
						</div>					
					</div>				   
					<div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Номер">
						</div>					
                        <div class="form-group col-md-6">
						    <input type="text" class="form-control" id="city" name="city" placeholder="Город">
							<?/*?>
							<select id="city" name="city" class="form-control">
								<option selected="true" disabled="disabled">Город</option>
									<?php
										$sql3 = $db_connect->query('SELECT * FROM `st_city_s`');
										while ($line3 = $sql3->fetchAssoc()) {
											echo '<option value="' . $line3['id'] . '">' . $line3['name_city'] . '</option>';
										}
									?>
                            </select>	
							<?*/?>							
						</div>
						<?/*?>
                        <div class="form-group col-md-6">
							<select id="status" name="status" class="form-control">
								<option selected="true" disabled="disabled">Статус</option>
									<?php
										$sql = $db_connect->query('SELECT * FROM `'. BEZ_DBPREFIX .'status`');
										while ($line = $sql->fetchAssoc()) {
											echo '<option value="' . $line['status_id'] . '">' . $line['status_name'] . '</option>';
										}
									?>
                            </select>
						</div>	
						<?*/?>		
					</div>	
					<?/*?>
					<div class="form-row">
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
                        <div class="form-group col-md-6">
                            <input type="date" class="form-control" id="date_create" name="date_create" placeholder="Дата">
						</div>						
					</div>	
					<?*/?>	
					<div class="form-row">
                        <div class="form-group col-12">
							<textarea class="form-control" rows="5" id="vopros" name="vopros" placeholder="Комментарий кольщика по вопросу клиента."></textarea>
						</div>						
					</div>	
					
					<div class="form-group account-btn text-center m-t-10">
						<div class="col-12">
							<button id="savebut" class="btn w-lg btn-rounded btn-primary waves-effect waves-light" type="submit">Добавить</button>
						</div>
					</div>
		
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

 <!-- Signup modal3 content -->
<div id="signup-modal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h2 class="text-center m-b-30">Добавление пользователя</h2>
				<form id="form-create-user" class="form-horizontal">
					<input type="hidden" id="idval" name="idval">
					<div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Пользователь">
						</div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="user" name="user" placeholder="Логин">
						</div>						
					</div>	
					<div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="login" name="login" placeholder="Почта">
						</div>	
                        <div class="form-group col-md-6">
							<select id="role" name="role" class="form-control">
								<option selected="true" disabled="disabled">Роль</option>
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
					</div>						
					<div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="password" class="form-control" id="pass" name="pass" placeholder="Пароль">
						</div>
                        <div class="form-group col-md-6">
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
							<button id="savebut" class="btn w-lg btn-rounded btn-primary waves-effect waves-light create-user-btn" type="button">Добавить</button>
						</div>
					</div>
		
				</form>
			</div>
		</div><!-- /.modal3-content -->
	</div><!-- /.modal3-dialog -->
</div><!-- /.modal3 -->
 <!-- Signup modal31 content -->
<div id="signup-modal31" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h2 class="text-center m-b-30">Обратная связь</h2>
				<p>Если у Вас возникли вопросы можете задать их здесь</p>
				<form class="form-horizontal" action="sendsupport.php" method="POST">
					<input type="hidden" id="idval" name="idval">
					<div class="form-row">
                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Имя">
						</div>					
					</div>	
					<div class="form-row">
                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" id="login" name="login" placeholder="Почта">
						</div>						
					</div>						
					<div class="form-row">
                        <div class="form-group col-md-12">
							<textarea id="mass" name="mass" class="form-control" rows="5" placeholder="Текст"></textarea>
						</div>						
					</div>	
					<div class="form-group account-btn text-center m-t-10">
						<div class="col-12">
							<button id="savebut" class="btn w-lg btn-rounded btn-primary waves-effect waves-light" type="submit">Отправить</button>
						</div>
					</div>
		
				</form>
			</div>
		</div><!-- /.modal31-content -->
	</div><!-- /.modal31-dialog -->
</div><!-- /.modal31 -->

<div id="signup-modal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h2 class="text-center m-b-30">Добавление лида</h2>
				<form class="form-horizontal" action="create-h2.php" method="POST">
					<input type="hidden" id="idval" name="idval">
					<div class="form-row">
                        <div class="form-group col-md-12">
                            <input type="hidden" id="fio" name="otdel" value="<?=$_SESSION['id_otdel']?>">
                            <input type="hidden" id="user" name="user" value="<?=$_SESSION['login_id']?>">
                            <input type="text" class="form-control" id="fio" name="fio" placeholder="ФИО">
                            <input type="hidden" id="status" name="status" value=<?= substr_count($_SERVER['REQUEST_URI'], 'lead-picker.php') > 0 ? "1" : "10" ?>> 
						</div>					
					</div>				   
					<div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Номер">
						</div>					
                        <div class="form-group col-md-6">
						    <input type="text" class="form-control" id="city" name="city" placeholder="Город">						
						</div>	
					</div>	
					<div class="form-row">
                        <div class="form-group col-12">
							<textarea class="form-control" rows="5" id="vopros" name="vopros" placeholder="Комментарий"></textarea>
						</div>						
					</div>	
					
					<div class="form-group account-btn text-center m-t-10">
						<div class="col-12">
							<button id="savebut" class="btn w-lg btn-rounded btn-primary waves-effect waves-light" type="submit">Добавить</button>
						</div>
					</div>
		
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="signup-modal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
        	<div class="modal-body">
                <h2 class="text-center m-b-30">Добавление партнера</h2>
                <form class="form-horizontal" action="create-partner-plan.php" method="POST">
                    <div class="form-group col-md-12">
                        <select id="partner" name="partner" class="form-control">
                            <option selected="true" disabled="disabled">Партнер</option>
                            <?php
								$sql3 = $db_connect->query('SELECT * FROM `st_partner_s`');
								while ($line3 = $sql3->fetchAssoc()) {
									echo '<option value="' . $line3['id'] . '">' . $line3['partner_name'] . '</option>';
								}									
							?>
                        </select>
                    </div>
                    <div class="form-group account-btn text-center m-t-10">
                        <div class="col-12">
                            <button id="savebut" class="btn w-lg btn-rounded btn-primary waves-effect waves-light" type="submit">Добавить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

		<!-- jQuery  -->
        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/popper.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/metisMenu.min.js"></script>
        <script src="/assets/js/waves.js"></script>
        <script src="/assets/js/jquery.slimscroll.js"></script>

        <!-- Flot chart -->
        <script src="/plugins/flot-chart/jquery.flot.min.js"></script>
        <script src="/plugins/flot-chart/jquery.flot.time.js"></script>
        <script src="/plugins/flot-chart/jquery.flot.tooltip.min.js"></script>
        <script src="/plugins/flot-chart/jquery.flot.resize.js"></script>
        <script src="/plugins/flot-chart/jquery.flot.pie.js"></script>
        <script src="/plugins/flot-chart/jquery.flot.crosshair.js"></script>
        <script src="/plugins/flot-chart/curvedLines.js"></script>
        <script src="/plugins/flot-chart/jquery.flot.axislabels.js"></script>

        <!-- KNOB JS -->
        <!--[if IE]>
        <script type="text/javascript" src="../plugins/jquery-knob/excanvas.js"></script>
        <![endif]-->
        <script src="/plugins/jquery-knob/jquery.knob.js"></script>




        <!-- Init js -->
        <script src="/assets/pages/jquery.form-pickers.init.js"></script>
		
        <!-- App js -->
        <script src="/assets/js/jquery.core.js"></script>
        <script src="/assets/js/jquery.app.js"></script>

        <!-- Modal-Effect -->
        <script src="/plugins/custombox/js/custombox.min.js"></script>
        <script src="/plugins/custombox/js/legacy.min.js"></script>
        <!-- Dashboard Init 
        <script src="/assets/js/modernizr.min.js"></script>
        <script src="/assets/pages/jquery.dashboard.init.js"></script>-->
		
		
		
        <!-- plugin js -->
        <script src="/plugins/moment/moment.js"></script>
        <script src="/plugins/bootstrap-timepicker/bootstrap-timepicker.js"></script>
        <script src="/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script src="/plugins/clockpicker/js/bootstrap-clockpicker.min.js"></script>
        <script src="/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="/plugins/datatables/jquery.dataTables.js"></script>		
        <script src="/plugins/datatables/dataTables.select.js"></script>		

		<script src="/assets/js/cast.js"></script>		
		
<?
if (isset($_SESSION['login_id'])) {$log_id = $_SESSION['login_id'];} else {$log_id = '';}

$url = $_SERVER['REQUEST_URI'];
$url = explode('?', $url);
$url = $url[0];

if($url === '/templates/pages/unprocessed-base-5.php' || $url === '/templates/pages/lead-sales.php') {
	$curdate = date('d/m/Y');
	$oper_date_s = $curdate;
	$oper_date_e = $curdate;
}
else {
	$res = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_start" AND login_id = "' . $log_id . '" ');	    						  
	$row = $res->fetchAssoc();	
	$res2 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_end" AND login_id = "' . $log_id . '" ');	    
	$row2 = $res2->fetchAssoc();

	if (isset($row['date_value']) and isset($row2['date_value'])) {
		$oper_date_s = date('d/m/Y', strtotime(strtr($row['date_value'], '-', '/')));	
		$oper_date_e = date('d/m/Y', strtotime(strtr($row2['date_value'], '-', '/')));	
	} else {
		$oper_date_s = date('d/m/Y');	
		$oper_date_e = date('d/m/Y');	
	}

}


?>


<script>
var startDate;
var endDate;

//if (startDate) {alert(1)};

$(function() {

    $('#reportrange').daterangepicker({
        //startDate: moment().subtract('days', 1),
        startDate: <? echo '"'.$oper_date_s.'"';?>,
        endDate: <? echo '"'.$oper_date_e.'"';?>,
		//maxDate: moment(),
		opens: 'left',
		buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-warning',
        cancelClass: 'btn-small',
        ranges: {
            'Сегодня': [moment(), moment()],
            'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'За 7 дней': [moment().subtract(6, 'days'), moment()],
            'За 30 дней': [moment().subtract(29, 'days'), moment()],
            'Этот месяц': [moment().startOf('month'), moment().endOf('month')],
            'Прошлый месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,'month').endOf('month')
            ]
        },
        locale: {
			  format: 'DD/MM/YYYY',
              applyLabel: 'Выбрать',
              cancelLabel: 'Отмена',
              fromLabel: 'от',
              toLabel: 'до',
              customRangeLabel: 'Другие даты',
              daysOfWeek: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
              monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
              firstDay: 1
          },
       function(start, end) {
        //console.info("Callback has been called!");
        $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        startDate = start;
        endDate = end;    
       }
    });	
	
});

</script>


    </body>
</html>