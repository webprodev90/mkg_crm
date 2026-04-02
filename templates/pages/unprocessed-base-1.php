<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base/unprocessed_base.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$unprocessed_base_obj = new UnprocessedBase();
$unprocessed_base_obj->set(
    array(
        'view_name' => 'unprocessed',
        'limit_start' => 0,
        'limit_end' => 500,
        'is_double' => 'n',
    )
);

$statuses_queryset = $unprocessed_base_obj->handle_action('get_statuses');
$statuses = '';
$statuses_search = '';
$statuses_for_lead = '';
foreach($statuses_queryset as $status) {
    if(($_SESSION['login_role'] == 1 && (int) $status['status_id'] == 9) || (int) $status['status_id'] != 9) {
        $statuses .= "<option value=\"{$status['status_id']}\">{$status['status_name']}</option>";
    }
}
foreach($statuses_queryset as $status) {
    if((int) $status['status_id'] !== 16) {
        $statuses_search .= "<option value=\"{$status['status_id']}\">{$status['status_name']}</option>";
    }
}
foreach($statuses_queryset as $status) {
    if(($_SESSION['login_role'] == 1 && (int) $status['status_id'] == 9) || ((int) $status['status_id'] == 6 || (int) $status['status_id'] == 15 || (int) $status['status_id'] == 16)) {
        $statuses_for_lead .= "<option value=\"{$status['status_id']}\">{$status['status_name']}</option>";
    }
}


$sources_queryset = $unprocessed_base_obj->handle_action('get_sources');
$sources_options = '';
foreach($sources_queryset as $source) {
    $sources_options .= "<option value=\"{$source['source']}\">{$source['source']}</option>";
}


$cities_queryset = $unprocessed_base_obj->handle_action('get_cities_group');
$cities_options = '';
$cities_group_options = '';
foreach($cities_queryset as $city) {
    $cities_options .= "<option value=\"{$city['id']}\">{$city['name']}</option>";
    $cities_group_options .= "<option value=\"{$city['name']}\">{$city['name']}</option>";
}

$operators = $unprocessed_base_obj->handle_action('get_operators');

$counters = $unprocessed_base_obj->handle_action('get_counters');

?>
<!-- Begin page -->
<div id="wrapper">

    <!-- Левое меню -->
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_left.php'; ?>

    <div class="content-page">
        <!-- Верхнее меню -->
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_top.php'; ?>

<?
		/*if ($_SESSION['id_otdel'] == '4') {
			echo'<pre>';print_r($_SESSION);echo'</pre>';
		}*/

?>


        <!-- Start Page content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                                <div class="row align-items-start">
                                    <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4) { ?>
                                        <div class="col-12 form-inline justify-content-center align-items-start" style="gap: 10px;">
                                            <div class="form-inline" style="gap: 10px;">
                                                <div class="event_counter" status="6">
                                                    Созвоны <span class="count_callings">
                                                        <?= $counters['callings']; ?>
                                                    </span>
                                                </div>
                                                <button type="submit" class="btn waves-effect btn-primary import-request"
                                                    data-toggle="modal" data-target="#import-csv-modal">
                                                    Загрузить csv
                                                </button>  
                                                <button class="btn waves-effect btn-primary" data-toggle="modal" data-target="#check-traffic-modal">
                                                    Проверить трафик
                                                </button>
                                            </div>
                                            <div>
                                                <div>
                                                    <input placeholder="Поиск..." type="text" class="form-control search-input" />
                                                    <button type="submit" class="btn waves-effect btn-primary search-request">ОК</button>
                                                </div>
                                                <div class="d-flex w-100 justify-content-end">
                                                    <input type="radio" id="search-choice1" name="search" value="search" checked />
                                                    <label for="search-choice1" class="pl-1 pr-2">Найти</label>
                                                    <input type="radio" id="search-choice2" name="search" value="go-to" />
                                                    <label for="search-choice2" class="pl-1">Перейти</label>
                                                </div> 
                                            </div>                                             
                                        </div>

                                    <?php } ?>
                                    <?php if($_SESSION['login_role'] == 5) { ?>
                                        <div class="col-12 form-inline justify-content-center align-items-baseline" style="gap: 10px;">
                                            <div class="event_counter ml-3" status="6">Созвоны
                                                <span class="count_callings">
                                                    <?= $counters['callings']; ?>
                                                </span>
                                            </div>
                                            <div class="event_counter ml-3" status="15">Лиды
                                                <span class="count_leads">
                                                    <?= $counters['leads']; ?>
                                                </span>
                                            </div>
                                            <div>
                                                <div>
                                                    <input placeholder="Поиск..." type="text" class="form-control search-input" />
                                                    <button type="submit" class="btn waves-effect btn-primary search-request">ОК</button>
                                                </div>
                                                <div class="d-flex w-100 justify-content-end">
                                                    <input type="radio" id="search-choice1" name="search" value="search" checked />
                                                    <label for="search-choice1" class="pl-1 pr-2">Найти</label>
                                                    <input type="radio" id="search-choice2" name="search" value="go-to" />
                                                    <label for="search-choice2" class="pl-1">Перейти</label>
                                                </div>         
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                           <?php /*if($_SESSION['login_role'] == 1) { ?>
                                            <div class="row col-md-6 csv-requestf">
                                                <div class="col-md-2 form-inline pr-0 pl-0">
                                                    <input placeholder="от" type="text" name="csv1-input" class="form-control csv1-input">
                                                </div>
                                                <div class="col-md-2 form-inline pr-0">
                                                    <input placeholder="до" type="text" name="csv2-input" class="form-control csv2-input">
                                                </div>
                                                <div class="col-md-3 form-inline">
                                                    <select name="id-status" class="form-control id-status">
                                                        <option selected="">Статус</option>
                                                        <?= $statuses_search; ?>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn waves-effect btn-primary csv-request">Найти</button> 
                                                <button id="modclick3" type="submit" data-csv-id="<?=$_SESSION['login_id'];?>" class="btn waves-effect btn-primary ml-2">Выгрузить csv</button> 
                                            </div>
                                            <?php }*/ ?>                                
                        </div>
                        <div class="mb-2">
                                <div class="d-flex justify-content-center" style="gap: 5px;">
                                    <!--
                                      <button class="btn btn-status" data-status="" >Все <span class="badge badge-light count_all_records"><?= $counters['all_records']; ?></span></button>
                                      <button class="btn btn-status lead-bg" data-status="15" >Лид <span class="badge badge-light count_leads_sale_request"><?= $counters['leads_sale_request']; ?></span></button>
                                      <button class="btn btn-status non-call-bg" data-status="8" >Недозвон <span class="badge badge-light count_non_call"><?= $counters['non_call']; ?></span></button>
                                      <button class="btn btn-status less-than-300-bg" data-status="21" >Долг менее 300 тысяч <span class="badge badge-light count_less_than_300"><?= $counters['less_than_300']; ?></span></button>
                                      <button class="btn btn-status rejection-bg" data-status="11" >Отказ <span class="badge badge-light count_rejection"><?= $counters['rejection']; ?></span></button>
                                      <button class="btn btn-status defect-bg" data-status="9" >Брак <span class="badge badge-light count_defect"><?= $counters['defect']; ?></span></button>
                                  -->
                                </div>
                        </div>
                        <div class="card-box">
                            <table class="table m-0 table-actions-bar nowrap" cellspacing="0" width="100%"
                                id="datatable1">
                                <thead>
                                    <tr class="table-head-tr">
                                        <th class="table-id align-middle">ID</th>
                                        <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4): ?>
                                            <th class="table-source align-middle">Источник</th>
                                        <?php endif; ?>
                                        <th class="table-phone align-middle">Телефон</th>
                                        <th class="table-name align-middle">Имя</th>
                                        <th class="table-city align-middle">Город</th>
                                        <th class="table-partner align-middle" style="display: none;">Партнер</th>
                                        <th class="table-comment align-middle">Комментарий</th>
                                        <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4): ?>
                                            <th class="table-operator align-middle">Колл</th>
                                        <?php endif; ?>
                                        <th class="table-status align-middle">Статус</th>
                                        <th class="table-date_time_status_change align-middle">Дата и время созвона/продажи лида</th>
                                        <th class="table-settings "></th>
                                    </tr>
                                </thead>
                                <tbody id="table-request">
                                    <?php $unprocessed_base_obj->handle_action('get_unprocessed_base'); ?>
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


<!-- Signup modal content -->
<div id="import-csv-modal" class="modal fade" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-center m-b-30">Импорт CSV</h2>
                <form target="_blank" action="/basep/parfbase.php" method="post" enctype="multipart/form-data"
                    class="form-horizontal">
                    <div class="form-group col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="source">Источник</label>
                                <select class="source-import-csv " name="source">
                                    <?= $sources_options; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="region">Регион</label>
                                <select class="region-import-csv " name="region">
                                    <?= $cities_group_options; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <input type="file" name="file" />
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <input type="checkbox" id="no-check-duplicates" name="no-check-duplicates" />
                                <label for="no-check-duplicates">Не проверять на дубли</label>
                            </div>
                        </div>
                    </div>

                    <input type="submit" class="d-none run-import" />

                </form>

                <div class="row form-group account-btn mt-4">
                    <div class="col-md-12 text-center">
                        <button class="btn w-lg btn-rounded btn-primary waves-effect waves-light"
                            onclick="$('.run-import').click();">
                            Сохранить
                        </button>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Signup modal content -->
<div id="check-traffic-modal" class="modal fade" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-center m-b-30">Проверка трафика</h2>
                <form target="_blank" action="/basep/check_traffic.php" method="post" enctype="multipart/form-data"
                    class="form-horizontal">
                    <div class="form-group col-md-12">
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <input type="file" name="file" />
                            </div>
                        </div>
                    </div>

                    <input type="submit" class="d-none check-traffic" />

                </form>

                <div class="row form-group account-btn mt-4">
                    <div class="col-md-12 text-center">
                        <button class="btn w-lg btn-rounded btn-primary waves-effect waves-light"
                            onclick="$('.check-traffic').click();">
                            Проверить
                        </button>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Signup modal content -->
<div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-center m-b-30">Редактирование заявки
                </h2>
                <input type="hidden" id="idval" name="id">
                <form class="form-horizontal form-request">
                    <div class="form-group col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="phone_number">Телефон</label>
                                <input type="text" name="phone_number" class="form-control" placeholder="Телефон" />
                            </div>
                            <div class="col-md-6">
                                <label for="fio">ФИО</label>
                                <input type="text" name="fio" class="form-control" placeholder="ФИО" />
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="city">Город</label>
                                <input type="text" name="city" class="form-control" placeholder="Город" />
                            </div>
                            <div class="col-md-6">
                                <label for="status">Статус <div class="back-to-status d-none">к выбору статуса</div>
                                </label>
                                <select class="form-control" name="status" id="status-modal">

                                </select>
                                <input type="text" name="date_time_status_change" id="datepicker-calling"
                                    class="form-control d-none">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="partner">Партнер</label>
                                <input type="text" name="partner" class="form-control" readonly placeholder="Партнер" />
                            </div>
                        </div>							
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="vopros">Комментарий</label>
                                <textarea type="text" name="vopros" class="form-control" is_lead="" original_text=""></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <input type="text" name="date_time_of_last_save" class="form-control d-none">
                            </div>
                        </div>  
                        <input type="hidden" id="past_status" name="past_status" />  
                    </div>

                </form>

                <div class="row form-group account-btn mt-4">
                    <div class="col-md-12 text-center">
                        <button id="save-request" class="btn w-lg btn-rounded btn-primary waves-effect waves-light mr-2">
                            Сохранить
                        </button>
                        <button id="send-sale-request" class="btn w-lg btn-rounded btn-warning waves-effect waves-light">
                            Продать
                        </button>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="d-none statuses">
    <?= $statuses; ?>
</div>

<div class="d-none statuses-for-lead">
    <?= $statuses_for_lead; ?>
</div>

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'); 
if (isset($_SESSION['id_atc'])) {$atc_id = $_SESSION['id_atc'];} else {$atc_id = 0;}
?>

<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/scripts/main.js"></script>


<script>
/*
$(document).ready(function() {
	var atc_id = <? echo '"'.$atc_id.'"';?>;
	
	if (atc_id > 0) {
		setInterval(function get_atc_cnt() {

				$.ajax({
                    method: "POST",
                    url: "/templates/corr-atc.php",
                    data: {atc_id: atc_id},
                    success: function(data) {
						if (data !== '0') {
								const is_lead = $('.form-request').find('.status_name').attr('data-lead');
								const statuses = + is_lead ? '.statuses-for-lead' : '.statuses';
								$('#signup-modal').modal('show');
								$('#idval').val(data.id);
								$('.form-request').find('[name="phone_number"]').val(data.phone_number);
								$('.form-request').find('[name="fio"]').val(data.fio);
								$('.form-request').find('[name="city"]').val(data.city);
								$($('.form-request').find('[name="status"]').html($(statuses).html())).val(data.status);
								$('.form-request').find('[name="partner"]').val(data.partner);
								$('.form-request').find('[name="vopros"]').val(data.vopros);	
								
								$('.form-request').serializeArray().filter(function (item) {
									if (item.name === 'date_time_status_change' && data.status == 6) {
										var momentDate = moment(item.value, 'DD.MM.YYYY HH:mm');
										item.value = momentDate.format('YYYY-MM-DDTHH:mm:ss');
									}

									if(item.name === 'date_time_of_last_save') {
										const now = moment();
										item.value = now.format('YYYY-MM-DD HH:mm:ss');                    
									}

									if(item.name ==='vopros' && is_lead === 1) {
										item.value = '';
									}

								return item.value !== '' && item.value !== 'Не установлено';
								});
								
						} else {
							//console.log(data);
						}
						
						
						
                    }
                });

		}, 1000);	
		
	}

});
*/
</script>