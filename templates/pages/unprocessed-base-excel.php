<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base_excel/unprocessed_base_excel.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$unprocessed_base_obj = new UnprocessedBaseExcel();
$curdate = date('Y-m-d');
$unprocessed_base_obj->set(
    array(
        'view_name' => 'unprocessed',
        'limit_start' => 0,
        'limit_end' => 5000,
        'date_start' => $curdate,
        'date_end' => $curdate,
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
if($sources_queryset != null) {
    foreach($sources_queryset as $source) {
        $sources_options .= "<option value=\"{$source['source']}\">{$source['source']}</option>";
    }    
}

$filtering_sources_queryset = $unprocessed_base_obj->handle_action('get_filtering_sources');
$sources_filtr = '';
if($filtering_sources_queryset != null) {
    foreach($filtering_sources_queryset as $sourcef) {
        $sources_filtr .= "<button class='btn btn-sourcesf lead-bg' data-sourcesf=\"{$sourcef['source']}\" >{$sourcef['source']} </button>";
    }    
}


$departments_queryset = $unprocessed_base_obj->handle_action('get_departments');
$departments_options = '';
foreach($departments_queryset as $department) {
    $departments_options .= "<option value=\"{$department['department_id']}\">{$department['name']}</option>";
}

$cities_group_sources_queryset = $unprocessed_base_obj->handle_action('get_cities_group_sources');
$cities_group_sources_options = '';
if($cities_group_sources_queryset != null) {
    foreach($cities_group_sources_queryset as $city) {
        $cities_group_sources_options .= "<option value=\"{$city['city_group']}\">{$city['name']}</option>";
    }
}

?>
<!-- Begin page -->
<div id="wrapper">

    <!-- Левое меню -->
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_left.php'; ?>

    <div class="content-page">
        <!-- Верхнее меню -->
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_top.php'; ?>

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
                                                <button type="submit" class="btn waves-effect btn-primary import-request"
                                                    data-toggle="modal" data-target="#import-csv-modal">
                                                    Загрузить csv
                                                </button>  
                                                <button class="btn waves-effect btn-primary" data-toggle="modal" data-target="#check-traffic-modal">
                                                    Проверить трафик
                                                </button>
                                                <button id="selrows" class="btn waves-effect btn-primary" data-toggle="modal" data-target="#select-rows"><i class="fi-square-check"></i></button>
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
                        </div>
<style>		
.table-chec {
    width: 2%;
	text-align: center;
}				
</style>						
                        <div class="mb-2">
						
						
							 <div class="buttons-by-statuses d-flex justify-content-center flex-wrap" style="gap: 7px;">
                                <?php if($filtering_sources_queryset != null) { ?>
                                    <button class='btn btn-sourcesf btn-sourcesf-all lead-bg' data-sourcesf="">Все</button>
                                <?php } ?>    
								<select style="width: 10%;background-color: #9ccff7;" class="form-control filter-region <?= $cities_group_sources_queryset ? '' : ' d-none'?>" name="geo">
                                    <option value="0" disabled selected>Регион</option>
                                    <?= $cities_group_sources_options; ?>
                                </select> 
                                <div class="buttons-by-sources d-flex flex-wrap" style="gap: 7px;">
                                    <?= $sources_filtr; ?>
                                </div>
							</div>
                        </div>
                        <div class="card-box">
                            <table class="table m-0 table-actions-bar nowrap" cellspacing="0" width="100%"
                                id="datatable1">
                                <thead>
                                    <tr class="table-head-tr">
										<th class="table-chec"><input type="checkbox" id="singleCheckbox1" value="option2"></th>
                                        <th class="table-id">ID</th>
                                        <th class="table-source">Источник</th>
                                        <th class="table-phone">Телефон</th>
                                        <th class="table-name">Имя</th>
                                        <th class="table-city-group-name">Регион</th>
                                        <th class="table-city">Город</th>
                                        <th class="table-partner" style="display: none;">Партнер</th>
                                        <th class="table-comment">Комментарий</th>
                                        <th class="table-date_time_status_change">Дата загрузки</th>
                                        <?/*?><th class="table-settings"></th><?*/?>
                                    </tr>
                                </thead>
                                <tbody id="table-request">
                                    <?php $unprocessed_base_obj->handle_action('get_unprocessed_base_excel'); ?>
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
                <form target="_blank" action="/basep/parfbase_excel.php" method="post" enctype="multipart/form-data"
                    class="form-horizontal">
                    <div class="form-group col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="source">Источник</label>
                                <select class="source-import-csv " name="source">
                                    <?= $sources_options; ?>
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

<div id="select-rows" class="modal fade" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-center m-b-30">Выбор записей</h2>
                <form action="update-selected-rows.php" method="post" class="form-horizontal">
                    <div class="form-group col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-12">
								<input type="hidden" class="form-control" id="sel_rows" name="sel_rows" placeholder="выбранные записи">
                                <label for="count_rows">Количество записей </label> <span class="textcnt"></span>
                                <input type="number" min="1" class="form-control" id="count_rows" name="count_rows" placeholder="Количество записей">
                            </div>
                           
                        </div>
                        <div class="row mt-2">                         
                            <div class="col-md-12">
                                <label for="department">Отдел</label>
                                <select class="form-control" name="department">
                                    <option value="0" disabled>Выберите отдел</option>
                                    <?= $departments_options; ?>
                                </select>
                            </div>
                        </div>

                    </div>
               
                    <div class="row form-group account-btn mt-4">
                        <div class="col-md-12 text-center">
							<button type="submit" name="submit" value="del" class="btn w-lg btn-rounded btn-danger waves-effect waves-light">Удалить</button>
                            <button type="submit" name="submit" class="btn w-lg btn-rounded btn-primary waves-effect waves-light">Отправить</button>
                        </div>
                    </div>
                            
                </form>

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

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'); ?>

<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/scripts/main.js"></script>
<script src="/scripts/common.js"></script>