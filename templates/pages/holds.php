<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base/unprocessed_base.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$unprocessed_base_obj = new UnprocessedBase();

$res1 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_start" AND login_id = "' . $_SESSION['login_id'] . '"');    
$oper_date_s = $res1->fetchAssoc(); 

$res2 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_end" AND login_id = "' . $_SESSION['login_id'] . '"');       
$oper_date_e = $res2->fetchAssoc(); 

$unprocessed_base_obj->set(
    array(
        'view_name' => 'unprocessed',
        'is_limit' => false,
        'date_start' => $oper_date_s['date_value'],
        'date_end' => $oper_date_e['date_value'],
        'is_double' => 'n',
        'holds' => 'y',
    )
);


$hold_statuses_queryset = $unprocessed_base_obj->handle_action('get_hold_statuses');
$hold_statuses_btn_form = '';

foreach($hold_statuses_queryset as $hold_status) {
    $hold_statuses_btn_form .= "<button type='button' class='btn btn-sm btn-status-form' data-status-id='{$hold_status['status_id']}'>{$hold_status['status_name']}</button>";
}

$filtering_sources_queryset = $unprocessed_base_obj->handle_action('get_filtering_sources');
$sources_filtr = '';
if($filtering_sources_queryset != null) {
    foreach($filtering_sources_queryset as $source) {
        $sources_filtr .= "<option value=\"{$source['source']}\">{$source['source']}</option>";
    }    
}

$filtering_cities_queryset = $unprocessed_base_obj->handle_action('get_filtering_cities');
$cities_filtr = '';
if($filtering_cities_queryset != null) {
    foreach($filtering_cities_queryset as $city) {
        $cities_filtr .= "<option value=\"{$city['city']}\">{$city['city']}</option>";
    }    
}

$filtering_cities_group_queryset = $unprocessed_base_obj->handle_action('get_filtering_cities_group');
$cities_group_filtr = '';
if($filtering_cities_group_queryset != null) {
    foreach($filtering_cities_group_queryset as $city) {
        $cities_group_filtr .= "<option value=\"{$city['id']}\">{$city['name']}</option>";
    }    
}

$filtering_operators_queryset = $unprocessed_base_obj->handle_action('get_operators');
$operators_filtr = '';
if($filtering_operators_queryset != null) {
    foreach($filtering_operators_queryset as $operator) {
        $operators_filtr .= "<option value=\"{$operator['id']}\">{$operator['name']}</option>";
    }    
}

$quantity_by_hold_statuses = $unprocessed_base_obj->handle_action('get_quantity_by_hold_statuses');
$buttons_by_hold_statuses = '';
foreach($quantity_by_hold_statuses as $quantity_by_status) {
    $buttons_by_hold_statuses .= "<button class='btn btn-status btn-info' data-status='{$quantity_by_status['status_id']}' >{$quantity_by_status['status_name']} <span class='badge badge-light'>{$quantity_by_status['count_status']}</span></button>";
}

$calls = $unprocessed_base_obj->handle_action('get_today_calls');

?>
<style>
<?php if($_SESSION['login_role'] == 5): ?>
    .table-comment {
        width: 28% !important;
    }
<?php endif; ?>

#table-request td.table-comment {
    padding-right: 15px !important;
}

.table-status {
    width: 5% !important;
}

</style>
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
                                    <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4 or $_SESSION['login_role'] == 12 or $_SESSION['login_role'] == 13) { ?>
                                        <div class="col-12 mb-3 form-inline justify-content-center align-items-start" style="gap: 10px;">
                                                <div class="form-inline">
                                                    <select class="form-control select-region">
                                                        <option value="" selected="">Выбор региона</option>
                                                        <?= $cities_group_filtr; ?>
                                                    </select>
                                                </div>                                                   
                                                <div class="form-inline">
                                                    <select class="select-city form-control">
                                                        <option value="" selected="">Выбор города</option>
                                                        <?= $cities_filtr; ?>
                                                     </select>
                                                </div>
                                                <div class="form-inline">
                                                    <select class="select-source form-control">
                                                        <option value="" selected="">Выбор источника</option>
                                                        <?= $sources_filtr; ?>
                                                     </select>
                                                </div>  
                                                <div class="form-inline">
                                                    <select class="select-all-operators form-control">
                                                        <option value="" selected="">Выбор пользователя</option>
                                                        <?= $operators_filtr; ?>
                                                     </select>
                                                </div>                                    
                                        </div>                                        
                                        <div class="col-12 form-inline justify-content-center align-items-start" style="gap: 10px;">
                                            <div>
                                                <button class='btn btn-danger btn-calls-today'>Созвоны на сегодня <span class='badge badge-light'><?= $calls['calls_today']; ?></span></button>
                                            </div>
                                            <div>
                                                <input placeholder="Поиск..." type="text" class="form-control search-input" />
                                                <button type="submit" class="btn waves-effect btn-primary search-request">ОК</button>
                                            </div>                                             
                                        </div>

                                    <?php } ?>
                                    <?php if($_SESSION['login_role'] == 5) { ?>
                                        <div class="col-12 mb-3 form-inline justify-content-center align-items-start" style="gap: 10px;">
                                                <div class="form-inline">
                                                    <select class="form-control select-region">
                                                        <option value="" selected="">Выбор региона</option>
                                                        <?= $cities_group_filtr; ?>
                                                    </select>
                                                </div>                                                   
                                                <div class="form-inline">
                                                    <select class="select-city form-control">
                                                        <option value="" selected="">Выбор города</option>
                                                        <?= $cities_filtr; ?>
                                                     </select>
                                                </div> 
                                                <div class="form-inline">
                                                    <select class="select-all-operators form-control">
                                                        <option value="" selected="">Выбор пользователя</option>
                                                        <?= $operators_filtr; ?>
                                                     </select>
                                                </div>                                   
                                        </div>  
                                        <div class="col-12 form-inline justify-content-center align-items-baseline" style="gap: 10px;">
                                            <div>
                                                <button class='btn btn-danger btn-calls-today'>Созвоны на сегодня <span class='badge badge-light'><?= $calls['calls_today']; ?></span></button>
                                            </div>
                                            <div>
                                                <input placeholder="Поиск..." type="text" class="form-control search-input" />
                                                <button type="submit" class="btn waves-effect btn-primary search-request">ОК</button>     
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>                              
                        </div>
                
                        <div class="mb-2">
                            <div class="buttons-by-statuses d-flex justify-content-center flex-wrap" style="gap: 7px;">
                                <button class='btn btn-status btn-secondary' data-status='' >Все</button>
                                <?= $buttons_by_hold_statuses; ?>      
                            </div>
                        </div>
                    
                        <div class="card-box">
                            <table class="table m-0 table-actions-bar nowrap" cellspacing="0" width="100%"
                                id="datatable1">
                                <thead>
                                    <tr class="table-head-tr">
                                        <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4 or $_SESSION['login_role'] == 12 or $_SESSION['login_role'] == 13): ?>
                                            <th class="table-chec align-middle"><input type="checkbox" id="singleCheckbox1" value="option2"></th>
                                        <?php endif; ?>
                                        <th class="table-id align-middle">ID</th>
                                        <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4 or $_SESSION['login_role'] == 12 or $_SESSION['login_role'] == 13): ?>
                                            <th class="table-source align-middle">Источник</th>
                                        <?php endif; ?>
                                        <th class="table-phone align-middle">Телефон</th>
                                        <th class="table-name align-middle">Имя</th>
                                        <th class="table-city align-middle">Город</th>
                                        <th class="table-partner align-middle" style="display: none;">Партнер</th>
                                        <th class="table-comment align-middle">Комментарий</th>
                                        <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4 or $_SESSION['login_role'] == 12 or $_SESSION['login_role'] == 13): ?>
                                            <th class="table-operator align-middle">Колл</th>
                                        <?php endif; ?>
                                        <th class="table-status align-middle">Статус</th>
                                        <th class="table-date_time_status_change align-middle">Дата и время продажи лида</th>
                                        <th class="table-hold-status align-middle">Статус Холда</th>
                                        <th class="table-date_time_hold_calling align-middle">Дата и время созвона</th>
                                        <th class="table-settings " data-call="<?= $_SESSION['login_role'] == 1 OR $_SESSION['id_atc'] > 100 ? '1' : '0' ?>"></th>
                                    </tr>
                                </thead>
                                <tbody id="table-request">
                                    
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
<div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-center m-b-30">Редактирование заявки
                </h2>
                <input type="hidden" id="idval" name="id">
                <input type="hidden" id="login_id" name="login_id" value="<?= $_SESSION['login_id']; ?>">
                <form class="form-horizontal form-request">

                    <div class="form-group col-md-12 mb-1">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="phone_number">Телефон</label>
                                <input type="text" name="phone_number" class="form-control" placeholder="Телефон" />
                            </div>
                            <div class="col-md-6">
                                <label for="auto-city">Регион по номеру</label>
                                <input type="text" id="auto-city" class="form-control" readonly placeholder="Регион по номеру" />
                            </div>                            
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="city">Город</label>
                                <input type="text" name="city" class="form-control" placeholder="Город" />
                                <div class="row mt-2 invalid-city d-none">
                                    <div class="col-md-12">
                                        <div class="text-danger">Необходимо заполнить поле</div>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <label for="fio">ФИО</label>
                                <input type="text" name="fio" class="form-control" placeholder="ФИО" />
                                <div class="row mt-2 invalid-fio d-none">
                                    <div class="col-md-12">
                                        <div class="text-danger">Необходимо заполнить поле</div>
                                    </div>
                                </div> 
                            </div>                            
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="partner">Партнер</label>
                                <input type="text" name="partner" class="form-control" readonly placeholder="Партнер" />
                            </div>
                        </div>
                        <input id="main_status_form" type="hidden" name="status" value="15">
                        <fieldset class="form-request-fieldset">
                            <legend class="form-request-legend">Статус Холда</legend>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" id="status_form" name="hold_status_id" value="">
                                    <div class="statuses-form" style="grid-template-columns: repeat(auto-fit, minmax(125px, 1fr));">
                                    <?= $hold_statuses_btn_form; ?>
                                    </div>
                                </div>  
                            </div>
                            <div class="row mt-2" id="date-time-calling">
                                <div class="col-md-12">
                                    <label for="date_time_hold_calling">Дата и время созвона</label>
                                    <input type="text" name="date_time_hold_calling" id="datepicker-calling" class="form-control">
                                </div>  
                            </div>
                            <div class="row mt-2 invalid-date-time d-none">
                                <div class="col-md-12">
                                    <div class="text-danger">Необходимо выбрать дату и время</div>
                                </div>
                            </div> 
                        </fieldset>
                        <?php if($_SESSION['login_id'] != 479) { ?>
                            <div class="text-center mt-4 wrapper-show-all-fields">
                                <button type="button" class="btn btn-secondary btn-sm show-all-fields">Показать все поля ▾</button>
                            </div>
                            <div id="fields-for-details" class="d-none">
                                <fieldset class="form-request-fieldset">
                                    <legend class="form-request-legend">Долги</legend>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="debt-banks">Банки</label>
                                            <input type="number" id="debt-banks" name="debt_banks" class="form-control" placeholder="0" min="0" />
                                        </div>
                                        <div class="col-md-4 pl-0">
                                            <label for="debt-mfo">МФО</label>
                                            <input type="number" id="debt-mfo" name="debt_mfo" class="form-control" placeholder="0" min="0" />
                                        </div>
                                        <div class="col-md-4 pl-0">
                                            <label for="taxes-fines">Налоги, штрафы</label>
                                            <input type="number" id="taxes-fines" name="taxes_fines" class="form-control" placeholder="0" min="0" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-5">
                                            <label for="debt-zhkh">ЖКХ</label>
                                            <input type="number" id="debt-zhkh" name="debt_zhkh" class="form-control" placeholder="0" min="0" />
                                        </div>
                                        <div class="col-md-7 pl-0">
                                            <label for="owners">Прописанных/собственников</label>
                                            <input type="number" id="owners" name="owners" class="form-control" placeholder="0" min="0" />
                                            <div class="row mt-2 invalid-owners d-none">
                                                <div class="col-md-12">
                                                    <div class="text-danger">Необходимо заполнить поле</div>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row mt-2 invalid-debt d-none">
                                        <div class="col-md-12">
                                            <div class="text-danger">Сумма задолженности должна быть больше 0</div>
                                        </div>
                                    </div>  
                                </fieldset>
                                <fieldset class="form-request-fieldset">
                                    <legend class="form-request-legend">Просрочки</legend>
                                    <div class="row">
                                        <div class="col-md-2 ml-4">
                                            <input class="form-check-input" type="radio" name="delays" id="delays-yes" value="y">
                                            <label class="form-check-label" for="delays-yes">
                                                Да
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <input class="form-check-input" type="radio" name="delays" id="delays-not" value="n">
                                            <label class="form-check-label" for="delays-not">
                                                Нет
                                            </label>    
                                        </div>                            
                                    </div>
                                    <div class="row mt-2 invalid-delays d-none">
                                        <div class="col-md-12">
                                            <div class="text-danger">Необходимо выбрать вариант</div>
                                        </div>
                                    </div> 
                                </fieldset>
                                <fieldset class="form-request-fieldset">
                                    <legend class="form-request-legend">Ипотека</legend>
                                    <div class="row">
                                        <div class="col-md-12 ml-4">
                                            <input class="form-check-input" type="radio" name="mortgage" id="mortgage-real-estate" value="m">
                                            <label class="form-check-label" for="mortgage-real-estate">
                                                Да+еще недвижимость
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 ml-4">
                                            <input class="form-check-input" type="radio" name="mortgage" id="only-mortgage" value="s">
                                            <label class="form-check-label" for="only-mortgage">
                                                Да, единственная
                                            </label>    
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 ml-4">
                                            <input class="form-check-input" type="radio" name="mortgage" id="mortgage-not" value="n">
                                            <label class="form-check-label" for="mortgage-not">
                                                Нет
                                            </label>    
                                        </div>                            
                                    </div>
                                    <div class="row mt-2 invalid-mortgage d-none">
                                        <div class="col-md-12">
                                            <div class="text-danger">Необходимо выбрать вариант</div>
                                        </div>
                                    </div> 
                                </fieldset>
                                <fieldset class="form-request-fieldset">
                                    <legend class="form-request-legend">Автокредит</legend>
                                    <div class="row">
                                        <div class="col-md-2 ml-4">
                                            <input class="form-check-input" type="radio" name="car_loan" id="car-loan-yes" value="y">
                                            <label class="form-check-label" for="car-loan-yes">
                                                Да
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <input class="form-check-input" type="radio" name="car_loan" id="car-loan-not" value="n">
                                            <label class="form-check-label" for="car-loan-not">
                                                Нет
                                            </label>    
                                        </div>                            
                                    </div>
                                    <div class="row mt-2 invalid-car-loan d-none">
                                        <div class="col-md-12">
                                            <div class="text-danger">Необходимо выбрать вариант</div>
                                        </div>
                                    </div> 
                                </fieldset>
                                <fieldset class="form-request-fieldset">
                                    <legend class="form-request-legend">Дополнительная недвижимость</legend>
                                    <div class="row">
                                        <div class="col-md-3 ml-4">
                                            <input class="form-check-input" type="checkbox" name="real_estate[]" value="1" id="real-estate-flat">
                                            <label class="form-check-label" for="real-estate-flat">
                                            Квартира
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <input class="form-check-input" type="checkbox" name="real_estate[]" value="2" id="real-estate-part">
                                            <label class="form-check-label" for="real-estate-part">
                                            Доля
                                            </label> 
                                        </div> 
                                        <div class="col-md-3">
                                            <input class="form-check-input" type="checkbox" name="real_estate[]" value="3" id="real-estate-country-house">
                                            <label class="form-check-label" for="real-estate-country-house">
                                            Дача
                                            </label> 
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 ml-4">
                                            <input class="form-check-input" type="checkbox" name="real_estate[]" value="4" id="real-estate-land">
                                            <label class="form-check-label" for="real-estate-land">
                                            Земля
                                            </label> 
                                        </div>   
                                        <div class="col-md-3">
                                            <input class="form-check-input" type="checkbox" name="real_estate[]" value="5" id="real-estate-share">
                                            <label class="form-check-label" for="real-estate-share">
                                            Пай
                                            </label> 
                                        </div>
                                        <div class="col-md-3">
                                            <input class="form-check-input" type="checkbox" name="real_estate[]" value="6" id="real-estate-сommercial">
                                            <label class="form-check-label" for="real-estate-сommercial">
                                            Коммерческая
                                            </label> 
                                        </div>                            
                                    </div>
                                    <div class="row mt-2 invalid-real-estate d-none">
                                        <div class="col-md-12">
                                            <div class="text-danger">Необходимо выбрать хотя бы один пункт</div>
                                        </div>
                                    </div>  
                                </fieldset>
                                <fieldset class="form-request-fieldset">
                                    <legend class="form-request-legend">Движимое имущество</legend>
                                    <div class="row">
                                        <div class="col-md-2 ml-4">
                                            <input class="form-check-input" type="checkbox" name="movables[]" value="1" id="movables-automobile">
                                            <label class="form-check-label" for="movables-automobile">
                                            Авто
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <input class="form-check-input" type="checkbox" name="movables[]" value="2" id="movables-motorbike">
                                            <label class="form-check-label" for="movables-motorbike">
                                            Мотоцикл
                                            </label> 
                                        </div> 
                                        <div class="col-md-2">
                                            <input class="form-check-input" type="checkbox" name="movables[]" value="3" id="movables-boat">
                                            <label class="form-check-label" for="movables-boat">
                                            Лодка
                                            </label> 
                                        </div> 
                                        <div class="col-md-3">
                                            <input class="form-check-input" type="checkbox" name="movables[]" value="4" id="movables-trailer">
                                            <label class="form-check-label" for="movables-trailer">
                                            Прицеп
                                            </label> 
                                        </div>  
                                    </div>
                                     <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label for="other-movables">Иное</label>
                                            <input type="text" name="other_movables" id="other-movables" class="form-control" placeholder="Иное" />
                                        </div>
                                    </div>  
                                    <div class="row mt-2 invalid-movables d-none">
                                        <div class="col-md-12">
                                            <div class="text-danger">Необходимо заполнить "Движимое имущество" или "Дополнительная недвижимость"</div>
                                        </div>
                                    </div>  
                                </fieldset>
                                <fieldset class="form-request-fieldset">
                                    <legend class="form-request-legend">Что предпринимали</legend>
                                    <div class="row">
                                        <div class="col-md-4 ml-4">
                                            <input class="form-check-input" type="checkbox" name="early_action[]" value="1" id="early-action-consultation">
                                            <label class="form-check-label" for="early-action-consultation">
                                            Консультация
                                            </label>
                                        </div>
                                        <div class="col-md-5">
                                            <input class="form-check-input" type="checkbox" name="early_action[]" value="2" id="early-action-agreement">
                                            <label class="form-check-label" for="early-action-agreement">
                                            Заключен договор
                                            </label> 
                                        </div> 
                                        <div class="col-md-2">
                                            <input class="form-check-input" type="checkbox" name="early_action[]" value="3" id="early-action-nothing">
                                            <label class="form-check-label" for="early-action-nothing">
                                            Ничего
                                            </label> 
                                        </div>  
                                    </div>
                                     <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label for="other-early-action">Иное</label>
                                            <input type="text" name="other_early_action" id="other-early-action" class="form-control" placeholder="Иное" />
                                        </div>
                                    </div>
                                    <div class="row mt-2 invalid-early-action d-none">
                                        <div class="col-md-12">
                                            <div class="text-danger">Необходимо выбрать хотя бы один пункт или заполнить поле</div>
                                        </div>
                                    </div>   
                                </fieldset>
                                <fieldset class="form-request-fieldset">
                                    <legend class="form-request-legend">Мессенджер</legend>
                                    <div class="row">
                                        <div class="col-md-2 ml-4">
                                            <input class="form-check-input" type="checkbox" name="messengers[]" value="1" id="messenger-tg">
                                            <label class="form-check-label" for="messenger-tg">
                                            TG
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <input class="form-check-input" type="checkbox" name="messengers[]" value="2" id="messenger-wa">
                                            <label class="form-check-label" for="messenger-wa">
                                            WA
                                            </label> 
                                        </div> 
                                        <div class="col-md-2">
                                            <input class="form-check-input" type="checkbox" name="messengers[]" value="3" id="messenger-vb">
                                            <label class="form-check-label" for="messenger-vb">
                                            VB
                                            </label> 
                                        </div>
                                        <div class="col-md-2">
                                            <input class="form-check-input" type="checkbox" name="messengers[]" value="4" id="messenger-max">
                                            <label class="form-check-label" for="messenger-max">
                                            MAX
                                            </label> 
                                        </div>   
                                    </div>
                                     <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label for="messenger-other-number">Номер, если отличается</label>
                                            <input type="text" name="messenger_phone_number" id="messenger-other-number" class="form-control" placeholder="Номер" />
                                        </div>
                                    </div>  
                                </fieldset>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label for="additional_comment">Дополнительный комментарий</label>
                                        <textarea type="text" name="additional_comment" class="form-control"></textarea>
                                        <div class="row mt-2 invalid-additional-comment d-none">
                                            <div class="col-md-12">
                                                <div class="text-danger">Необходимо заполнить поле</div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="vopros">Комментарий</label>
                                    <textarea type="text" name="vopros" class="form-control"></textarea>
                                </div>
                            </div> 
                        <?php } ?>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <input type="text" name="date_time_of_last_save" class="form-control d-none">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <input type="text" name="date_time_lead_save" class="form-control d-none">
                            </div>
                        </div>  
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <input type="text" name="date_time_status_change" class="form-control d-none">
                            </div>
                        </div>
                        <input type="hidden" id="past_status" name="past_status" />  
                        <?php if($_SESSION['login_id'] != 479) { ?>
                            <input type="hidden" id="vopros" name="vopros" /> 
                        <?php } ?>  
                    </div>

                </form>

                <div class="row m-0">
                    <div class="col-md-6">
                        <label for="update-status" class="label-checkbox m-0">Обновить статус <input class="checkbox-update-status" type="checkbox" name="update-status" /></label>
                    </div>
                </div> 

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

                <div class="row mt-2 text-center validation-warning d-none">
                    <div class="col-md-12">
                        <div class="text-danger">Не удалось сохранить! Заполните все поля (подсвечено красным)!</div>
                    </div>
                </div> 

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Sale modal content -->
<div id="audio-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-center m-b-30">Аудиозаписи разговоров
                </h2>
                <div class="audio-links m-3" style="font-size: 16px;">

                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Sale modal content -->
<div id="sale-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-center m-b-30">История продаж
                </h2>
                <form class="form-horizontal">
                    <input type="hidden" id="idval" name="id">
                    <div class="form-group col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-12">                               
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Партнер</th>
                                            <th scope="col">Стоимость</th>
                                            <th scope="col">Дата</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-sales"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>


            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'); ?>

<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/scripts/main.js"></script>
<script src="/scripts/sale.js"></script>
<script src="/scripts/audio.js"></script>
<script src="/scripts/common.js"></script>
