<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base/unprocessed_base.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/sale/sale.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/admin_checklist/admin_checklist.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$unprocessed_base_obj = new UnprocessedBase();
$curdate = date('Y-m-d');


$unprocessed_base_obj->set(
    array(
        'view_name' => 'sales',
        'limit_start' => 0,
        'limit_end' => 500,
        'date_start' => $curdate,
        'date_end' => $curdate,
        'comparison_operator' => '>',
        'filter' => array(
            'comparison_operator' => 'IN',
            'status' => '15'
        ),
        'order_by' => 'date_time_status_change DESC',
        'page' => 'lead-sales',
        'manual_sal' => '1',
    )
);

$statuses_queryset = $unprocessed_base_obj->handle_action('get_statuses');
$statuses = '';
$statuses_btn_form = '';
foreach($statuses_queryset as $status) {
    $statuses .= "<option value=\"{$status['status_id']}\">{$status['status_name']}</option>";
}
foreach($statuses_queryset as $status) {
    $statuses_btn_form .= "<button type='button' class='btn btn-sm btn-status-form' data-status-id='{$status['status_id']}'>{$status['status_name']}</button>";
}


$sale = new Sale();

$partners = $unprocessed_base_obj->handle_action('get_partners');
$plan_queryset = $sale->handle_action('get_plan');
$fix_plan_queryset = $sale->handle_action('get_fix_plan');

$admin_checklist = new AdminChecklist();
$admins = $admin_checklist->handle_action('get_admins');

$err = isset($_GET['err']) ? 'Неверный логин или пароль' : false;
?>
<!-- Begin page -->
<div id="wrapper">

    <!-- Левое меню -->
    <? require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_left.php'; ?>

    <div class="content-page">
        <!-- Верхнее меню -->
        <? require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_top.php'; ?>

        <!-- Start Page content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-10">
                        <div class="list-plan">
                            <?php if(!empty($plan_queryset)): ?>
                                <?php $total_sold_request = 0; ?>
                                <?php $total_count = 0; ?>
                                <?php foreach($plan_queryset as $plan): ?>
                                    <div class="d-flex justify-content-between <?= $plan['is_working_hours']; ?>">
                                        <div class="ml-1 plan-partner-name" title="Партнер">
                                            <?= $plan['partner_name']; ?>
                                        </div>
                                        <div class="d-flex">
                                            <div class="ml-1 plan-current-request" title="Продано">
                                                <?= $plan['sold_request']; ?>
                                            </div><span class="text-dark">/</span>
                                            <div class="plan-max-request mr-2" title="Необходимо продать">
                                                <?= $plan['count']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $total_sold_request += (int) $plan['sold_request']; ?>
                                    <?php $total_count += (int) $plan['count']; ?>                                    
                                <?php endforeach; ?>
                                <div class="d-flex justify-content-between">
                                    <div class="ml-1 plan-partner-name" title="Партнер">
                                        <u>Общее</u>
                                    </div>
                                    <div class="d-flex">
                                        <div class="ml-1 plan-current-request" title="Продано">
                                            <?= $total_sold_request; ?>
                                        </div>/
                                        <div class="plan-max-request mr-2" title="Необходимо продать">
                                            <?= $total_count; ?>
                                        </div>
                                    </div>    
                                </div>                                
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="d-flex flex-column justify-content-end align-items-end mb-2">
                            <a href="#" class="d-flex open-rate" data-toggle="modal" data-target="#rate-modal">
                                <i class="fa fa-cog mr-1" aria-hidden="true">
                                </i>
                                <div>Статистика</div>
                            </a>
                            <a href="#" class="d-flex open-plan" data-toggle="modal" data-target="#plan-modal">
                                <i class="fa fa-cog mr-1" aria-hidden="true">
                                </i>
                                <div>Настроить план</div>
                            </a>
                            <a href="#" class="d-flex open-partners" data-toggle="modal" data-target="#open-partners">
                                <i class="fa fa-cog mr-1 mt-1" aria-hidden="true"></i>
                                <div>Партнеры</div>
                            </a>
                        </div>
                        <div class="d-flex flex-column justify-content-end align-items-end mb-2" style="text-align: right;">
                            <?php if(!empty($admins)): ?>
                                <?php foreach($admins as $admin): ?>
                                    <a href="#" class="d-flex open-rate mb-1 checklist-btn <?= $admin['is_completed_tasks'] ? 'text-success' : 'text-danger'; ?>" data-user-id="<?= $admin['id']; ?>" data-toggle="modal" data-target="#open-checklist">
                                        
                                        
                                        <div><i class="fa fi-paper mr-1" aria-hidden="true"></i>Чеклист (<?= $admin['name']; ?>)</div>
                                    </a>                                  
                                <?php endforeach; ?>                             
                            <?php endif; ?>                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">

                            <table class="table m-0 table-actions-bar nowrap" cellspacing="0" width="100%"
                                id="datatable1">
                                <thead>
                                    <tr class="table-head-tr">
                                        <th class="table-id">ID</th>
                                        <th class="table-phone">Телефон</th>
                                        <th class="table-name">Имя</th>
                                        <th class="table-city">Город</th>
                                        <th class="table-comment" style="width:30%;">Комментарий</th>
                                        <th class="table-new-lead">Лид</th>
                                        <th class="table-operator">Оператор</th>
                                        <th class="table-settings"></th>
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
<div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-center m-b-30">Редактирование заявки
                </h2>
                <form class="form-horizontal form-request">
                    <input type="hidden" id="idval" name="id">

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
                        <fieldset class="form-request-fieldset">
                            <legend class="form-request-legend">Статус</legend>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" id="status_form" name="status" value="">
                                    <div class="statuses-form">
                                    <?= $statuses_btn_form; ?>
                                    </div>
                                </div>  
                            </div>
                            <div class="row mt-2" id="date-time-calling">
                                <div class="col-md-12">
                                    <label for="date_time_status_change">Дата и время созвона</label>
                                    <input type="text" name="date_time_status_change" id="datepicker-calling" class="form-control">
                                </div>  
                            </div>
                            <div class="row mt-2 invalid-date-time d-none">
                                <div class="col-md-12">
                                    <div class="text-danger">Необходимо выбрать дату и время</div>
                                </div>
                            </div> 
                        </fieldset>
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
                        <input type="hidden" id="vopros" name="vopros" />  
                    </div>

                </form>

                <div class="row form-group account-btn mt-4">
                    <div class="col-md-12 text-center">
                        <button id="save-request" class="btn w-lg btn-rounded btn-primary waves-effect waves-light">
                            Сохранить
                        </button>
                    </div>
                </div>

                <div class="row mt-2 text-center validation-warning d-none">
                    <div class="col-md-12">
                        <div class="text-danger">Не удалось сохранить! Заполните все поля (подсвечено красным)!</div>
                    </div>
                </div> 

                </form>


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
                <h2 class="text-center m-b-30">Продажа заявки
                </h2>
                <form class="form-horizontal">
                    <input type="hidden" id="idval" name="id">
                    <div class="form-group col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="partner-id">Партнер</label>
                                <select class="form-control" name="partner-id" id="partner-id">
                                    <?php foreach($partners as $partner): ?>
                                        <?php if($partner['active'] == 1): ?>
                                            <option value="<?= $partner['id']; ?>">
                                                <?= $partner['partner_name']; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-end mb-3">
                                    <label class="fw-500 mb-0">История продаж</label>
                                    <button id="reject-partner" type="button" class="btn w-lg btn-rounded btn-danger waves-effect waves-light">
                                        Забраковать
                                    </button>
                                </div>                                
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
				<div class="warning-part"></div>
                <div class="row form-group account-btn mt-4">
                    <div class="col-md-12 text-center">
                        <button id="sale-request" class="btn w-lg btn-rounded btn-primary waves-effect waves-light">
                            Продать
                        </button>
                    </div>
                </div>

                </form>


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
<div id="rate-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-center m-b-30">Статистика</h2>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-9">
                            <input type="text" id="rate-date-range" class="form-control" name="dateselector">
                        </div>
                        <div class="col-md-3">
                            <button class="btn waves-effect waves-light btn-primary" id="btn-rate">Задать</button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <table id="rate-table" class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Отдел</th>
                                        <th scope="col">ID</th>
                                        <th scope="col">ФИО</th>
                                        <th scope="col">Лиды</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row form-group account-btn mt-4">
                        <div class="col-md-12 text-center">
                            <button id="close-rate-modal" onclick="$('#rate-modal').modal('hide');"
                                class="btn w-lg btn-rounded btn-primary waves-effect waves-light">
                                Закрыть
                            </button>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<!-- Sale modal content -->
<div id="plan-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="max-width: min-content;">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-center m-b-30">Настройка плана
                </h2>
                <form class="form-horizontal">
                    <!-- <input type="hidden" id="idval" name="id"> -->
                    <div class="form-group col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="added-plan"></th>
                                            <th scope="col" class="partner-plan">Партнер</th>
                                            <th scope="col" class="count-plan">Количество</th>
                                            <th scope="col" class="time-start">Начало</th>
                                            <th scope="col" class="time-end">Окончание</th>
                                            <th scope="col" class="city_check">Проверка города</th>
                                            <th scope="col" class="audio_is_check">Отправка аудиоссылки на разговор</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-plan">
                                        <?php foreach($fix_plan_queryset as $plan): ?>
                                            <tr partner_id="<?= $plan['id']; ?>">
                                                <td class="align-middle">
                                                    <input name="added-plan" type="checkbox" class="form-control" <?= $plan['is_added'] ? ' checked' : ''; ?> />
                                                </td>
                                                <td class="align-middle">
                                                    <?= $plan['partner_name']; ?>
                                                </td>
                                                <td>
                                                    <input name="count" type="number" min="0" class="form-control" value="<?= $plan['count']; ?>">
                                                </td>
                                                <td>
                                                    <input name="time_start" type="time" class="form-control" value="<?= $plan['time_start']; ?>">
                                                </td>
                                                <td>
                                                    <input name="time_end" type="time" class="form-control" value="<?= $plan['time_end']; ?>">
                                                </td>
                                                <td class="align-middle">
                                                    <input name="city_check" type="checkbox" class="form-control" <?= $plan['is_city'] ? ' checked' : ''; ?> />
                                                </td>
                                                <td class="align-middle">
                                                    <input name="audio_is_check" type="checkbox" class="form-control" <?= $plan['is_audio'] ? ' checked' : ''; ?> />
                                                </td>												
                                            </tr>
                                        <?php endforeach; ?>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row form-group account-btn mt-4">
                    <div class="col-md-12 text-center">
                        <button id="save-plan" class="btn w-lg btn-rounded btn-primary waves-effect waves-light">
                            Сохранить
                        </button>
                    </div>
                </div>

                </form>


            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Sale modal content -->
<div id="open-partners" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-center m-b-30">Партнеры
                </h2>
                <form class="form-horizontal">
                    <!-- <input type="hidden" id="idval" name="id"> -->
                    <div class="form-group col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="partner-plan">Партнер</th>
                                            <th scope="col" class="count-plan">Активность</th>
                                            <th scope="col" class="remove-plan"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-partners">
                                        <?php foreach($partners as $partner): ?>
                                            <tr>
                                                <td><?= $partner['partner_name']; ?></td>
                                                <td>
                                                    <select class="partner-status-patch" id="<?= $partner['id']; ?>">
                                                        <option value="1" <?php if($partner['active'] == 1)
                                                            echo 'selected'; ?>>Активен</option>
                                                        <option value="0" <?php if($partner['active'] == 0)
                                                            echo 'selected'; ?>>Не активен</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Sale modal content -->
<div id="open-checklist" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="max-width: min-content;">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-center">Чеклист</h2>
                <h3 id="checklist-user-name" class="text-center text-muted m-b-30"></h3>
                <div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div id="checklist" class="mb-3">
                            </div>
                            <div class="btn-create-admin-task">
                                <button id="create-admin-task" class="btn btn-primary btn-sm">Добавить задачу</button>
                            </div>
                            <form class="form-horizontal form-admin-task d-none">
                                <div class="form-group col-md-12 p-0">
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label for="new-task">Новая задача</label>
                                            <textarea id="new-task" type="text" name="new-task" class="form-control"></textarea>
                                        </div>
                                    </div> 
                                    <div class="row mt-2">
                                        <button id="add-new-task" type="button" class="btn btn-sm btn-success ml-3">Добавить</button>
                                        <button id="create-task-cancel" type="button" class="btn btn-sm btn-danger ml-2">Отменить</button>
                                    </div>                
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="d-none statuses">
    <?= $statuses; ?>
</div>

<div class="d-none partners">
    <?php foreach($partners as $partner): ?>
        <option value="<?= $partner['id']; ?>">
            <?= $partner['partner_name']; ?>
        </option>
    <?php endforeach; ?>
</div>

<div class="d-none active-partners">
    <?php foreach($partners as $partner): ?>
        <?php if($partner['active'] == 1): ?>
            <option value="<?= $partner['id']; ?>">
                <?= $partner['partner_name']; ?>
            </option>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'); ?>

<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="/scripts/main.js"></script>
<script src="/scripts/sale.js"></script>
<script src="/scripts/admin_checklist.js"></script>